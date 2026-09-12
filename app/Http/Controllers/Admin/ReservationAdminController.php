<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\TimeSlot;
use App\Models\PricingProfile;
use App\Models\RoomRate;
use App\Models\Contact;
use App\Models\Checkin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ReservationConfirmed;
use App\Mail\QuoteSent;
use Stripe\Stripe;
use Stripe\Refund;
use Illuminate\Support\Facades\Log;

class ReservationAdminController extends Controller
{
    public function index()
    {
        $reservations = Reservation::latest()->get();
        return view('admin.reservations.index', compact('reservations'));
    }

    public function create()
    {
        $rooms = Room::where('active', true)->orderBy('name')->get();
        $timeSlots = TimeSlot::where('active', true)->orderBy('order_index')->get();
        $profiles = PricingProfile::where('active', true)->orderBy('id')->get();

        return view('admin.reservations.create', compact('rooms', 'timeSlots', 'profiles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'time_slot_id' => 'required|exists:time_slots,id',
            'pricing_profile_id' => 'required|exists:pricing_profiles,id',
            'date' => 'required|date',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'status' => 'required|in:paid,pending,gratuit,devis',
            'custom_price' => 'nullable|numeric|min:0',
            'devis_notes' => 'nullable|string|max:2000',
        ]);

        $date = Carbon::parse($request->date)->startOfDay();
        $timeSlot = TimeSlot::findOrFail($request->time_slot_id);

        // Vérifier conflit (devis bloque aussi le créneau)
        $exists = Reservation::where('room_id', $request->room_id)
            ->where('time_slot_id', $request->time_slot_id)
            ->whereDate('date', $date->toDateString())
            ->whereIn('status', ['pending', 'paid', 'gratuit', 'devis'])
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['date' => 'Ce créneau est déjà réservé pour cette date.']);
        }

        // Prix : custom si renseigné, sinon tarif standard
        $rate = RoomRate::where('room_id', $request->room_id)
            ->where('time_slot_id', $request->time_slot_id)
            ->where('pricing_profile_id', $request->pricing_profile_id)
            ->first();

        if ($request->filled('custom_price')) {
            $price = (float) $request->custom_price;
        } else {
            $price = $request->status === 'gratuit' ? 0 : ($rate->price ?? 0);
        }

        $startAt = $date->copy()->setTimeFromTimeString($timeSlot->start_time);
        $endAt = $date->copy()->setTimeFromTimeString($timeSlot->end_time);

        $isDevis = $request->status === 'devis';

        $reservation = Reservation::create([
            'room_id' => $request->room_id,
            'time_slot_id' => $request->time_slot_id,
            'pricing_profile_id' => $request->pricing_profile_id,
            'date' => $date->toDateString(),
            'start_at' => $startAt,
            'end_at' => $endAt,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'price' => $price,
            'status' => $isDevis ? 'devis' : ($request->status === 'gratuit' ? 'paid' : $request->status),
            'payment_method' => $request->status === 'paid' ? $request->payment_method : ($request->status === 'gratuit' ? 'gratuit' : null),
            'devis_token' => $isDevis ? Str::random(48) : null,
            'devis_notes' => $isDevis ? $request->devis_notes : null,
        ]);

        // Créer/mettre à jour le contact
        $nameParts = explode(' ', $request->name, 2);
        $firstname = $nameParts[0];
        $lastname = $nameParts[1] ?? '';

        Contact::firstOrCreate(
            ['email' => $request->email],
            ['firstname' => $firstname, 'lastname' => $lastname, 'phone' => $request->phone]
        );

        // Envoyer le devis par email
        if ($isDevis) {
            Mail::to($reservation->email)->send(new QuoteSent($reservation));

            return redirect()->route('admin.reservations.index')
                ->with('success', 'Devis cree et envoye par email a ' . $reservation->email);
        }

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Réservation créée manuellement.');
    }

    public function importForm()
    {
        return view('admin.reservations.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls',
            'default_status' => 'required|in:paid,pending,gratuit',
            'default_payment_method' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        // Lire le fichier CSV
        $rows = [];
        if (in_array($extension, ['csv', 'txt'])) {
            $handle = fopen($file->getRealPath(), 'r');
            $header = null;
            while (($line = fgetcsv($handle, 0, ';')) !== false) {
                if (!$header) {
                    // Nettoyer BOM UTF-8
                    $line[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $line[0]);
                    $header = array_map('strtolower', array_map('trim', $line));
                    continue;
                }
                if (count($line) === count($header)) {
                    $rows[] = array_combine($header, $line);
                }
            }
            fclose($handle);
        }

        if (empty($rows)) {
            return back()->with('error', 'Fichier vide ou format non reconnu. Utilisez un CSV avec separateur point-virgule (;).');
        }

        $imported = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $i => $row) {
            $lineNum = $i + 2;

            $roomName = trim($row['salle'] ?? '');
            $date = trim($row['date'] ?? '');
            $slotCode = trim($row['creneau'] ?? '');
            $name = trim($row['client'] ?? $row['nom'] ?? '');
            $email = trim($row['email'] ?? '');
            $phone = trim($row['telephone'] ?? $row['tel'] ?? '');

            if (!$roomName || !$date || !$slotCode || !$name) {
                $errors[] = "Ligne $lineNum : champs obligatoires manquants (salle, date, creneau, client)";
                $skipped++;
                continue;
            }

            $room = Room::where('name', 'LIKE', "%$roomName%")->first();
            if (!$room) {
                $errors[] = "Ligne $lineNum : salle '$roomName' introuvable";
                $skipped++;
                continue;
            }

            $timeSlot = TimeSlot::where('code', $slotCode)
                ->orWhere('label', 'LIKE', "%$slotCode%")
                ->first();
            if (!$timeSlot) {
                $errors[] = "Ligne $lineNum : creneau '$slotCode' introuvable";
                $skipped++;
                continue;
            }

            // Parser la date (dd/mm/yyyy ou yyyy-mm-dd)
            try {
                $parsedDate = str_contains($date, '/')
                    ? Carbon::createFromFormat('d/m/Y', $date)->startOfDay()
                    : Carbon::parse($date)->startOfDay();
            } catch (\Exception $e) {
                $errors[] = "Ligne $lineNum : date '$date' invalide";
                $skipped++;
                continue;
            }

            // Vérifier conflit
            $exists = Reservation::where('room_id', $room->id)
                ->where('time_slot_id', $timeSlot->id)
                ->whereDate('date', $parsedDate->toDateString())
                ->whereIn('status', ['pending', 'paid'])
                ->exists();

            if ($exists) {
                $errors[] = "Ligne $lineNum : creneau deja reserve ($roomName, $date, $slotCode)";
                $skipped++;
                continue;
            }

            $profile = PricingProfile::where('active', true)->first();
            $rate = RoomRate::where('room_id', $room->id)
                ->where('time_slot_id', $timeSlot->id)
                ->where('pricing_profile_id', $profile->id)
                ->first();

            $status = $request->default_status === 'gratuit' ? 'paid' : $request->default_status;
            $price = $request->default_status === 'gratuit' ? 0 : ($rate->price ?? 0);
            $paymentMethod = $status === 'paid' ? ($request->default_payment_method ?? 'autre') : null;
            if ($request->default_status === 'gratuit') $paymentMethod = 'gratuit';

            $startAt = $parsedDate->copy()->setTimeFromTimeString($timeSlot->start_time);
            $endAt = $parsedDate->copy()->setTimeFromTimeString($timeSlot->end_time);

            Reservation::create([
                'room_id' => $room->id,
                'time_slot_id' => $timeSlot->id,
                'pricing_profile_id' => $profile->id,
                'date' => $parsedDate->toDateString(),
                'start_at' => $startAt,
                'end_at' => $endAt,
                'name' => $name,
                'email' => $email ?: 'import@laccordeur.gf',
                'phone' => $phone ?: '',
                'price' => $price,
                'status' => $status,
                'payment_method' => $paymentMethod,
            ]);

            if ($email) {
                $nameParts = explode(' ', $name, 2);
                Contact::firstOrCreate(
                    ['email' => $email],
                    ['firstname' => $nameParts[0], 'lastname' => $nameParts[1] ?? '']
                );
            }

            $imported++;
        }

        $message = "$imported reservation(s) importee(s).";
        if ($skipped > 0) $message .= " $skipped ignoree(s).";

        return redirect()->route('admin.reservations.index')
            ->with('success', $message)
            ->with($errors ? 'error' : 'info', implode(' | ', $errors));
    }

    public function show(Reservation $reservation)
{
    $reservation->load([
        'room',
        'timeSlot',
        'pricingProfile',
    ]);

    return view('admin.reservations.show', compact('reservation'));
}

public function resendEmail(Reservation $reservation)
{
    if ($reservation->status === 'devis') {
        Mail::to($reservation->email)->send(new QuoteSent($reservation));
        return back()->with('success', 'Devis renvoye par email.');
    }

    Mail::to($reservation->email)
        ->send(new ReservationConfirmed($reservation));

    return back()->with('success', 'Email de confirmation renvoyé.');
}
    public function confirmPayment(Request $request, Reservation $reservation)
    {
        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Cette réservation n\'est pas en attente.');
        }

        $request->validate([
            'payment_method' => 'required|string',
        ]);

        $reservation->update([
            'status' => 'paid',
            'payment_method' => $request->payment_method,
        ]);

        return back()->with('success', 'Paiement validé pour la réservation #' . $reservation->id);
    }

public function cancelAndRefund(Reservation $reservation)
{
    // Devis ou en attente → annulation simple
    if (in_array($reservation->status, ['pending', 'devis'])) {
        $reservation->update(['status' => 'cancelled']);
        return back()->with('success', 'Réservation #' . $reservation->id . ' annulée.');
    }

    if ($reservation->status !== 'paid') {
        return back()->with('error', 'Cette réservation ne peut pas être annulée.');
    }

    // Réservation payée sans Stripe (manuelle) → annulation simple
    if (!$reservation->stripe_session_id) {
        $reservation->update(['status' => 'cancelled']);
        return back()->with('success', 'Réservation #' . $reservation->id . ' annulée.');
    }

    // Réservation payée via Stripe → annulation + remboursement
    try {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::retrieve($reservation->stripe_session_id);

        if (!$session->payment_intent) {
            throw new \Exception('PaymentIntent introuvable.');
        }

        Refund::create([
            'payment_intent' => $session->payment_intent,
        ]);

        $reservation->update(['status' => 'cancelled']);

        return back()->with('success', 'Réservation annulée et remboursée avec succès.');

    } catch (\Throwable $e) {

        Log::error('Erreur remboursement Stripe', [
            'reservation_id' => $reservation->id,
            'error' => $e->getMessage(),
        ]);

        return back()->with('error', 'Erreur lors du remboursement Stripe.');
    }
}




}

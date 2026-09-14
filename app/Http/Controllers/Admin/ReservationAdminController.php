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
use App\Models\ReservationSupplement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ReservationConfirmed;
use App\Mail\QuoteSent;
use App\Mail\SupplementPaymentRequest;
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
        $profiles = PricingProfile::where('active', true)->orderBy('sort_order')->orderBy('id')->get();

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

        // Vérifier conflit (inclut chevauchements AM/PM/Journée)
        if (Reservation::hasConflict($request->room_id, $request->time_slot_id, $date->toDateString())) {
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

        // Horaires : custom si renseigné, sinon du créneau
        $startTime = $request->filled('custom_start') ? $request->custom_start : $timeSlot->start_time;
        $endTime = $request->filled('custom_end') ? $request->custom_end : $timeSlot->end_time;
        $startAt = $date->copy()->setTimeFromTimeString($startTime);
        $endAt = $date->copy()->setTimeFromTimeString($endTime);

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
            'event_name' => $request->event_name ?: null,
            'event_visibility' => $request->event_name ? ($request->event_visibility ?: 'private') : 'private',
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
        $extension = strtolower($file->getClientOriginalExtension());

        $rows = [];

        if (in_array($extension, ['csv', 'txt'])) {
            // CSV
            $handle = fopen($file->getRealPath(), 'r');
            $header = null;
            while (($line = fgetcsv($handle, 0, ';')) !== false) {
                if (!$header) {
                    $line[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $line[0]);
                    $header = array_map('strtolower', array_map('trim', $line));
                    continue;
                }
                if (count($line) === count($header)) {
                    $rows[] = array_combine($header, $line);
                }
            }
            fclose($handle);
        } elseif (in_array($extension, ['xlsx', 'xls'])) {
            // Excel
            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray(null, true, true, false);

                $header = null;
                foreach ($data as $line) {
                    if (!$header) {
                        $header = array_map('strtolower', array_map('trim', array_map('strval', $line)));
                        continue;
                    }
                    if (count($line) === count($header)) {
                        $values = array_map(function ($v) {
                            if ($v instanceof \DateTimeInterface) {
                                return $v->format('d/m/Y');
                            }
                            return trim((string) ($v ?? ''));
                        }, $line);
                        $row = array_combine($header, $values);
                        if (array_filter($row)) {
                            $rows[] = $row;
                        }
                    }
                }
            } catch (\Exception $e) {
                return back()->with('error', 'Erreur de lecture du fichier Excel : ' . $e->getMessage());
            }
        }

        if (empty($rows)) {
            return back()->with('error', 'Fichier vide ou format non reconnu. Utilisez un CSV (;) ou un fichier Excel (.xlsx).');
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
            $eventName = trim($row['evenement'] ?? $row['event'] ?? '');
            $eventVisibility = trim($row['visibilite'] ?? $row['visibility'] ?? 'private');
            $rowProfile = trim($row['profil'] ?? '');
            $rowStatus = strtolower(trim($row['statut'] ?? $row['status'] ?? ''));
            $rowPayment = trim($row['reglement'] ?? $row['paiement'] ?? '');
            $rowStartTime = trim($row['heure_debut'] ?? $row['debut'] ?? '');
            $rowEndTime = trim($row['heure_fin'] ?? $row['fin'] ?? '');

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

            // Vérifier conflit (inclut chevauchements AM/PM/Journée)
            if (Reservation::hasConflict($room->id, $timeSlot->id, $parsedDate->toDateString())) {
                $errors[] = "Ligne $lineNum : creneau deja reserve ($roomName, $date, $slotCode)";
                $skipped++;
                continue;
            }

            // Profil : par ligne ou par defaut
            if ($rowProfile) {
                $profile = PricingProfile::where('active', true)
                    ->where(function ($q) use ($rowProfile) {
                        $q->where('code', strtoupper($rowProfile))
                          ->orWhere('label', 'LIKE', "%$rowProfile%");
                    })->first();
                if (!$profile) {
                    $errors[] = "Ligne $lineNum : profil '$rowProfile' introuvable";
                    $skipped++;
                    continue;
                }
            } else {
                $profile = PricingProfile::where('active', true)->first();
            }

            $rate = RoomRate::where('room_id', $room->id)
                ->where('time_slot_id', $timeSlot->id)
                ->where('pricing_profile_id', $profile->id)
                ->first();

            // Statut : par ligne ou par defaut
            $statusMap = [
                'paye' => 'paid', 'payee' => 'paid', 'paid' => 'paid',
                'attente' => 'pending', 'pending' => 'pending', 'en attente' => 'pending',
                'gratuit' => 'gratuit',
                'annule' => 'cancelled', 'annulee' => 'cancelled', 'cancelled' => 'cancelled',
            ];
            $effectiveStatus = $rowStatus ? ($statusMap[$rowStatus] ?? $request->default_status) : $request->default_status;

            $status = $effectiveStatus === 'gratuit' ? 'paid' : $effectiveStatus;
            $price = $effectiveStatus === 'gratuit' ? 0 : ($rate->price ?? 0);

            // Reglement : par ligne ou par defaut
            $effectivePayment = $rowPayment ?: $request->default_payment_method;
            $paymentMethod = $status === 'paid' ? ($effectivePayment ?? 'autre') : null;
            if ($effectiveStatus === 'gratuit') $paymentMethod = 'gratuit';

            // Horaires : custom si renseigné, sinon du créneau
            $startTime = $this->normalizeTime($rowStartTime ?: $timeSlot->start_time);
            $endTime = $this->normalizeTime($rowEndTime ?: $timeSlot->end_time);
            $startAt = $parsedDate->copy()->setTimeFromTimeString($startTime);
            $endAt = $parsedDate->copy()->setTimeFromTimeString($endTime);

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
                'event_name' => $eventName ?: null,
                'event_visibility' => in_array($eventVisibility, ['public', 'private']) ? $eventVisibility : 'private',
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
        'supplements',
        'invoices',
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

// ─── Supplements ───────────────────────────────────────────

public function addSupplement(Request $request, Reservation $reservation)
{
    $request->validate([
        'label' => 'required|string|max:255',
        'description' => 'nullable|string|max:2000',
        'amount' => 'required|numeric|min:0.01',
        'action_type' => 'required|in:send_link,manual',
        'payment_method' => 'required_if:action_type,manual|nullable|string',
    ]);

    $supplement = $reservation->supplements()->create([
        'label' => $request->label,
        'description' => $request->description,
        'amount' => $request->amount,
        'status' => $request->action_type === 'manual' ? 'paid' : 'pending',
        'payment_method' => $request->action_type === 'manual' ? $request->payment_method : null,
        'token' => $request->action_type === 'send_link' ? Str::random(48) : null,
    ]);

    if ($request->action_type === 'send_link') {
        Mail::to($reservation->email)->send(new SupplementPaymentRequest($supplement));
        return back()->with('success', 'Complement ajoute et lien de paiement envoye a ' . $reservation->email);
    }

    return back()->with('success', 'Complement ajoute et marque comme paye.');
}

public function resendSupplementEmail(ReservationSupplement $supplement)
{
    if ($supplement->status !== 'pending' || !$supplement->token) {
        return back()->with('error', 'Ce complement n\'est pas en attente de paiement.');
    }

    $supplement->load('reservation');
    Mail::to($supplement->reservation->email)->send(new SupplementPaymentRequest($supplement));

    return back()->with('success', 'Lien de paiement renvoye.');
}

public function confirmSupplement(Request $request, ReservationSupplement $supplement)
{
    if ($supplement->status !== 'pending') {
        return back()->with('error', 'Ce complement n\'est pas en attente.');
    }

    $request->validate(['payment_method' => 'required|string']);

    $supplement->update([
        'status' => 'paid',
        'payment_method' => $request->payment_method,
    ]);

    return back()->with('success', 'Complement marque comme paye.');
}

public function cancelSupplement(ReservationSupplement $supplement)
{
    $supplement->update(['status' => 'cancelled']);
    return back()->with('success', 'Complement annule.');
}

// ─── Remise ────────────────────────────────────────────────

public function applyDiscount(Request $request, Reservation $reservation)
{
    $request->validate([
        'discount_type' => 'required|in:fixed,percent',
        'discount_value' => 'required|numeric|min:0',
        'discount_label' => 'nullable|string|max:255',
    ]);

    if ($request->discount_type === 'percent') {
        $percent = min($request->discount_value, 100);
        $amount = round($reservation->price * $percent / 100, 2);
        $label = $request->discount_label ?: "Remise {$percent}%";
    } else {
        $amount = min($request->discount_value, $reservation->price);
        $label = $request->discount_label ?: "Remise {$amount} EUR";
    }

    $reservation->update([
        'discount_amount' => $amount,
        'discount_label' => $label,
    ]);

    return back()->with('success', "Remise de " . number_format($amount, 2, ',', ' ') . " EUR appliquee.");
}

public function removeDiscount(Reservation $reservation)
{
    $reservation->update([
        'discount_amount' => 0,
        'discount_label' => null,
    ]);

    return back()->with('success', 'Remise supprimee.');
}

private function normalizeTime(string $time): string
{
    $time = trim($time);
    // Already HH:MM or HH:MM:SS
    if (preg_match('/^\d{1,2}:\d{2}(:\d{2})?$/', $time)) {
        return $time;
    }
    // 14h30, 9h30, 18h30
    if (preg_match('/^(\d{1,2})h(\d{1,2})$/', $time, $m)) {
        return $m[1] . ':' . str_pad($m[2], 2, '0', STR_PAD_LEFT);
    }
    // 14h, 9h
    if (preg_match('/^(\d{1,2})h$/', $time, $m)) {
        return $m[1] . ':00';
    }
    return $time;
}

}

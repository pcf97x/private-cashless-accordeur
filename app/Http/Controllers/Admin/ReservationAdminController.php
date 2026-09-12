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
            'status' => 'required|in:paid,pending,gratuit',
        ]);

        $date = Carbon::parse($request->date)->startOfDay();
        $timeSlot = TimeSlot::findOrFail($request->time_slot_id);

        // Vérifier conflit
        $exists = Reservation::where('room_id', $request->room_id)
            ->where('time_slot_id', $request->time_slot_id)
            ->whereDate('date', $date->toDateString())
            ->whereIn('status', ['pending', 'paid', 'gratuit'])
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors(['date' => 'Ce créneau est déjà réservé pour cette date.']);
        }

        // Prix
        $rate = RoomRate::where('room_id', $request->room_id)
            ->where('time_slot_id', $request->time_slot_id)
            ->where('pricing_profile_id', $request->pricing_profile_id)
            ->first();

        $price = $request->status === 'gratuit' ? 0 : ($rate->price ?? 0);

        $startAt = $date->copy()->setTimeFromTimeString($timeSlot->start_time);
        $endAt = $date->copy()->setTimeFromTimeString($timeSlot->end_time);

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
            'status' => $request->status === 'gratuit' ? 'paid' : $request->status,
            'payment_method' => $request->status === 'paid' ? $request->payment_method : ($request->status === 'gratuit' ? 'gratuit' : null),
        ]);

        // Créer/mettre à jour le contact
        $nameParts = explode(' ', $request->name, 2);
        $firstname = $nameParts[0];
        $lastname = $nameParts[1] ?? '';

        Contact::firstOrCreate(
            ['email' => $request->email],
            ['firstname' => $firstname, 'lastname' => $lastname, 'phone' => $request->phone]
        );

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Réservation créée manuellement.');
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
    // Réservation en attente → annulation simple
    if ($reservation->status === 'pending') {
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

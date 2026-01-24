<?php

namespace App\Http\Controllers;


use App\Models\PricingProfile;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomRate;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{

public function index()
{
    $rooms = Room::where('active', true)->orderBy('name')->get();
    return view('reservation.index', compact('rooms'));
}
public function show(Room $room)
{
    $timeSlots = TimeSlot::where('active', true)->orderBy('order_index')->get();
    $pricingProfiles = PricingProfile::where('active', true)->orderBy('id')->get();
    $user = auth()->user();

    return view('reservation.show', [
        'room' => $room,
        'timeSlots' => $timeSlots,

        // garde-fous: certains de tes blades utilisent $profiles, d’autres $pricingProfiles
        'profiles' => $pricingProfiles,
        'pricingProfiles' => $pricingProfiles,

        // garde-fou: certains blades utilisent $user
        'user' => $user,
    ]);
}


    /**
     * AJAX: renvoie prix + dispo
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'time_slot_id' => 'required|exists:time_slots,id',
            'pricing_profile_id' => 'required|exists:pricing_profiles,id',
            'date' => 'required|date',
        ]);

        $date = Carbon::parse($request->date)->toDateString();

        // Anti-conflit : on bloque si pending OU paid
        $exists = Reservation::where('room_id', $request->room_id)
            ->where('time_slot_id', $request->time_slot_id)
            ->whereDate('date', $date)
            ->whereIn('status', ['pending', 'paid'])
            ->exists();

        if ($exists) {
             return response()->json([
        'available' => false,
        'price' => null,
        'message' => 'Créneau déjà réservé',
    ]);
        }

        
        $rate = RoomRate::where('room_id', $request->room_id)
        ->where('time_slot_id', $request->time_slot_id)
        ->where('pricing_profile_id', $request->pricing_profile_id)
        ->first();

   

        if (!$rate) {
            return response()->json([
                'available' => true,
                'price' => null,
                'message' => 'Aucun tarif trouvé pour cette combinaison',
            ]);
        }

      return response()->json([
        'price' => $rate?->price
    ]);
    }

    /**
     * Crée la réservation en pending, puis redirige vers /reservation/{id}/pay
     */
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
    ]);

    return DB::transaction(function () use ($request) {

        $date = Carbon::parse($request->date)->startOfDay();

        // 🔐 Anti-conflit TOTAL (pending + paid)
        $exists = Reservation::where('room_id', $request->room_id)
            ->where('time_slot_id', $request->time_slot_id)
            ->whereDate('date', $date->toDateString())
            ->whereIn('status', ['pending', 'paid'])
            ->lockForUpdate()
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['date' => 'Créneau déjà réservé']);
        }

        // Prix
        $rate = RoomRate::where('room_id', $request->room_id)
            ->where('time_slot_id', $request->time_slot_id)
            ->where('pricing_profile_id', $request->pricing_profile_id)
            ->first();

        if (!$rate) {
            return back()
                ->withInput()
                ->withErrors(['price' => 'Aucun tarif trouvé']);
        }

        $timeSlot = TimeSlot::findOrFail($request->time_slot_id);

        $startAt = $date->copy()->setTimeFromTimeString($timeSlot->start_time);
        $endAt   = $date->copy()->setTimeFromTimeString($timeSlot->end_time);

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
            'price' => $rate->price,
            'status' => 'pending',
        ]);

       if ($request->expectsJson()) {
    return response()->json([
        'checkout_url' => route('reservation.pay', $reservation),
    ]);
}

return redirect()->route('reservation.pay', $reservation);
    });
}


    /**
     * Crée la Checkout Session et redirige Stripe
     */
    public function pay(Reservation $reservation)
    {
        if ($reservation->status === 'paid') {
            return redirect()->route('reservation.success', $reservation);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::create([
            'mode' => 'payment',
            'customer_email' => $reservation->email,
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => (int) round($reservation->price * 100),
                    'product_data' => [
                        'name' => 'Réservation salle',
                        'description' => "Salle #{$reservation->room_id} - {$reservation->start_at} → {$reservation->end_at}",
                    ],
                ],
            ]],
            'success_url' => route('reservation.success', ['reservation' => $reservation->id]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('reservation.cancel', ['reservation' => $reservation->id]),
        ]);

        $reservation->update([
            'stripe_session_id' => $session->id,
        ]);

        return redirect($session->url);
    }

    public function success(Reservation $reservation, Request $request)
    {
        $sessionId = $request->query('session_id');

        // Sécurité : si on a un session_id, on vérifie chez Stripe
        if ($sessionId) {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = StripeSession::retrieve($sessionId);

            if ($session && $session->payment_status === 'paid') {
                $reservation->update([
                    'status' => 'paid',
                    'stripe_session_id' => $session->id,
                ]);
            }
        }

        return view('reservation.success', compact('reservation'));
    }

    public function cancel(Reservation $reservation)
    {
        // Option: tu peux mettre cancelled ici si tu veux.
        return view('reservation.cancel', compact('reservation'));
    }
}

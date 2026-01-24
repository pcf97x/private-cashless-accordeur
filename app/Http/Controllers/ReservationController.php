<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomRate;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class ReservationController extends Controller
{
    public function index()
    {
        $rooms = Room::where('active', true)->orderBy('id')->get();
        return view('reservation.index', compact('rooms'));
    }

    public function show(Room $room)
    {
        $timeSlots = TimeSlot::where('active', 1)->orderBy('order_index')->get();
        $pricingProfiles = \App\Models\PricingProfile::where('active', 1)->get();

        $user = auth()->user();

        return view('reservation.show', compact('room', 'timeSlots', 'pricingProfiles', 'user'));
    }

    public function calculatePrice(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'time_slot_id' => 'required|exists:time_slots,id',
            'pricing_profile_id' => 'required|exists:pricing_profiles,id',
        ]);

        $rate = RoomRate::where([
            'room_id' => $request->room_id,
            'time_slot_id' => $request->time_slot_id,
            'pricing_profile_id' => $request->pricing_profile_id,
        ])->first();

        return response()->json([
            'price' => $rate ? (float) $rate->price : null,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'time_slot_id' => 'required|exists:time_slots,id',
            'pricing_profile_id' => 'required|exists:pricing_profiles,id',
            'date' => 'required|date',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        $slot = TimeSlot::findOrFail($request->time_slot_id);

        $start = Carbon::parse($request->date . ' ' . $slot->start_time);
        $end   = Carbon::parse($request->date . ' ' . $slot->end_time);

        // Prix
        $rate = RoomRate::where([
            'room_id' => $request->room_id,
            'time_slot_id' => $request->time_slot_id,
            'pricing_profile_id' => $request->pricing_profile_id,
        ])->first();

        if (!$rate) {
            return back()
                ->withInput()
                ->withErrors(['price' => "Aucun tarif trouvé pour cette combinaison"]);
        }

        /**
         * Anti-conflit:
         * - bloque les réservations payées
         * - bloque aussi les pending récentes (15 minutes) pour éviter double paiement
         */
        $pendingCutoff = now()->subMinutes(15);

        $conflict = Reservation::where('room_id', $request->room_id)
            ->where(function ($q) use ($pendingCutoff) {
                $q->where('status', 'paid')
                  ->orWhere(function ($q2) use ($pendingCutoff) {
                      $q2->where('status', 'pending')
                         ->where('created_at', '>=', $pendingCutoff);
                  });
            })
            ->where('start_at', '<', $end)
            ->where('end_at', '>', $start)
            ->exists();

        if ($conflict) {
            return back()->withErrors(['slot' => 'Créneau déjà réservé'])->withInput();
        }

        // Create reservation (pending)
        $reservation = Reservation::create([
            'room_id' => $request->room_id,
            'time_slot_id' => $request->time_slot_id,
            'pricing_profile_id' => $request->pricing_profile_id,
            'date' => Carbon::parse($request->date)->startOfDay(),
            'start_at' => $start,
            'end_at' => $end,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'price' => $rate->price,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('reservation.pay', ['reservation' => $reservation->id]);
    }

    public function pay(Reservation $reservation)
    {
        // Déjà payé => on ne repaie pas
        if ($reservation->status === 'paid') {
            return redirect()->route('reservation.success', ['reservation' => $reservation->id]);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'customer_email' => $reservation->email,
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => (int) round(((float) $reservation->price) * 100),
                    'product_data' => [
                        'name' => 'Réservation salle',
                        'description' => "Salle #{$reservation->room_id} - {$reservation->start_at} → {$reservation->end_at}",
                    ],
                ],
            ]],
            'metadata' => [
                'reservation_id' => (string) $reservation->id,
            ],
            'success_url' => route('reservation.success', ['reservation' => $reservation->id]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('reservation.cancel', ['reservation' => $reservation->id]),
        ]);

        $reservation->update([
            'stripe_session_id' => $session->id,
        ]);

        return redirect()->away($session->url);
    }

    public function success(Request $request, Reservation $reservation)
    {
        // En local, si webhook pas branché, on peut "finaliser" via session_id
        $sessionId = $request->query('session_id');

        if ($sessionId && $reservation->status !== 'paid') {
            Stripe::setApiKey(config('services.stripe.secret'));

            try {
                $session = StripeSession::retrieve($sessionId);

                if (($session->payment_status ?? null) === 'paid') {
                    $reservation->update([
                        'status' => 'paid',
                        'stripe_session_id' => $session->id,
                    ]);
                }
            } catch (\Throwable $e) {
                // on ne bloque pas l’affichage
            }
        }

        return view('reservation.success', compact('reservation'));
    }

    public function cancel(Reservation $reservation)
    {
        // Option: tu peux mettre canceled ici si tu veux
        // $reservation->update(['status' => 'canceled']);

        return view('reservation.cancel', compact('reservation'));
    }
}

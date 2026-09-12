<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;
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
public function cancelAndRefund(Reservation $reservation)
{
    // Sécurité
    if ($reservation->status !== 'paid') {
        return back()->with('error', 'Cette réservation ne peut pas être remboursée.');
    }

    if (!$reservation->stripe_session_id) {
        return back()->with('error', 'Aucune session Stripe associée.');
    }

    try {
        Stripe::setApiKey(config('services.stripe.secret'));

        // Récupération de la session Stripe
        $session = \Stripe\Checkout\Session::retrieve($reservation->stripe_session_id);

        if (!$session->payment_intent) {
            throw new \Exception('PaymentIntent introuvable.');
        }

        // Remboursement
        Refund::create([
            'payment_intent' => $session->payment_intent,
        ]);

        // Update BDD
        $reservation->update([
            'status' => 'cancelled',
        ]);

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

<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Response;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        $secret = config('services.stripe.webhook_secret'); // STRIPE_WEBHOOK_SECRET

        // Si pas de secret (ex: local sans stripe cli), on refuse proprement
        if (!$secret) {
            return response('Webhook secret missing', 400);
        }

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Throwable $e) {
            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $reservationId = $session->metadata->reservation_id ?? null;

            if ($reservationId) {
                $reservation = Reservation::find($reservationId);
                if ($reservation) {
                    $reservation->update([
                        'status' => 'paid',
                        'stripe_session_id' => $session->id,
                    ]);
                }
            }
        }

        return new Response('OK', 200);
    }
}

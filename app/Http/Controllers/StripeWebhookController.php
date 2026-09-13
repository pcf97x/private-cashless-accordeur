<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Contact;
use App\Models\Checkin;
use App\Services\WeezeventParticipantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ReservationConfirmed;
use App\Mail\ReservationAdminNotification;
use App\Mail\CustomNeedsNotification;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Response;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        $secret = config('services.stripe.webhook_secret');

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

            // Chercher par metadata OU par stripe_session_id
            $reservationId = $session->metadata->reservation_id ?? null;
            $reservation = null;

            if ($reservationId) {
                $reservation = Reservation::find($reservationId);
            }

            if (!$reservation && $session->id) {
                $reservation = Reservation::where('stripe_session_id', $session->id)->first();
            }

            if ($reservation && $reservation->status !== 'paid') {
                $reservation->update([
                    'status' => 'paid',
                    'stripe_session_id' => $session->id,
                ]);

                $reservation->load('room');

                // Créer/mettre à jour le contact
                $nameParts = explode(' ', $reservation->name, 2);
                $firstname = $nameParts[0];
                $lastname = $nameParts[1] ?? '';

                $contact = Contact::firstOrCreate(
                    ['email' => $reservation->email],
                    [
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'phone' => $reservation->phone,
                    ]
                );

                $contact->update([
                    'phone' => $reservation->phone ?: $contact->phone,
                    'firstname' => $contact->firstname ?: $firstname,
                    'lastname' => $contact->lastname ?: $lastname,
                ]);

                // Créer un checkin avec QR code
                $checkin = Checkin::create([
                    'contact_id' => $contact->id,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'email' => $reservation->email,
                    'purpose' => 'Réservation ' . ($reservation->room->name ?? ''),
                    'qr_token' => (string) Str::uuid(),
                ]);

                // Weezevent participant
                try {
                    $weezevent = app(WeezeventParticipantService::class);
                    $response = $weezevent->createParticipant([
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'email' => $reservation->email,
                    ]);

                    $participant = $response['participants'][0] ?? null;
                    if ($participant) {
                        $checkin->update([
                            'weez_participant_id' => $participant['id_participant'] ?? null,
                            'weez_ticket_code' => $participant['barcode_id'] ?? null,
                            'weez_event_id' => $participant['id_evenement'] ?? null,
                        ]);
                    }
                } catch (\Exception $e) {
                    // Weezevent peut échouer, on continue
                }

                // Emails
                try {
                    Mail::to($reservation->email)
                        ->send(new ReservationConfirmed($reservation));
                    // Notification conciergerie (toutes les reservations)
                    Mail::to(config('mail.conciergerie_address', 'laconciergerie@groupe-aprosep.com'))
                        ->send(new ReservationAdminNotification($reservation));
                } catch (\Exception $e) {
                    // Email peut échouer, on continue
                }
            }
        }

        return new Response('OK', 200);
    }
}

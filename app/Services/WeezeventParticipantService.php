<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeezeventParticipantService
{
    public function createParticipant(array $data): array
    {
        $url = 'https://api.weezevent.com/v3/participants'
            . '?api_key=' . config('services.weezevent.api_key')
            . '&access_token=' . config('services.weezevent.access_token');

        $payload = [
            'participants' => [
                [
                    'id_evenement' => (int) config('services.weezevent.event_id'),
                    'id_billet'    => (int) config('services.weezevent.ticket_id'),
                    'email'        => $data['email'],
                    'nom'          => $data['lastname'],
                    'prenom'       => $data['firstname'],
                
                ]
            ],
            'notify' => false
        ];

        $response = Http::asForm()               // 👈 IMPORTANT
            ->withoutVerifying()                // DEV WAMP
            ->post($url, [
                'api_key' => config('services.weezevent.api_key'),
                'data'    => json_encode($payload), // 👈 EXACTEMENT COMME POSTMAN
            ]);

        if ($response->failed()) {
            throw new \Exception(
                'Erreur Weezevent : HTTP ' . $response->status()
                . ' | Raw: ' . $response->body()
            );
        }

        return $response->json();
    }
}

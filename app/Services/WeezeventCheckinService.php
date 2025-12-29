<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeezeventCheckinService
{
    public function fetchParticipants(int $eventId): array
    {
        $response = Http::withOptions([
            'verify' => false // WAMP SSL
        ])->get("https://api.weezevent.com/v3/evenement/{$eventId}/participants", [
            'api_key' => config('services.weezevent.api_key'),
            'access_token' => config('services.weezevent.access_token'),
            'limit' => 500,
        ]);

        if ($response->failed()) {
            throw new \Exception(
                'Erreur Weezevent : '.$response->status().' '.$response->body()
            );
        }

        return $response->json();
    }
}

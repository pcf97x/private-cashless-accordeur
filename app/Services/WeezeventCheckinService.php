<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeezeventCheckinService
{
    protected function getAccessToken(): string
    {
        // Cache le token 1h (Weezevent tokens durent longtemps mais on rafraîchit régulièrement)
        return Cache::remember('weezevent_access_token', 3600, function () {
            $response = Http::withOptions(['verify' => false])
                ->asForm()
                ->post('https://api.weezevent.com/auth/access_token', [
                    'username' => config('services.weezevent.username'),
                    'password' => config('services.weezevent.password'),
                    'api_key'  => config('services.weezevent.api_key'),
                ]);

            if ($response->failed()) {
                // Fallback sur le token statique du .env
                return config('services.weezevent.access_token');
            }

            return $response->json('accessToken');
        });
    }

    public function fetchParticipants(int $eventId): array
    {
        $token = $this->getAccessToken();

        $response = Http::withOptions([
            'verify' => false
        ])->get("https://api.weezevent.com/v3/evenement/{$eventId}/participants", [
            'api_key' => config('services.weezevent.api_key'),
            'access_token' => $token,
            'limit' => 500,
        ]);

        // Si 401, on force un nouveau token et on retry
        if ($response->status() === 401) {
            Cache::forget('weezevent_access_token');
            $token = $this->getAccessToken();

            $response = Http::withOptions([
                'verify' => false
            ])->get("https://api.weezevent.com/v3/evenement/{$eventId}/participants", [
                'api_key' => config('services.weezevent.api_key'),
                'access_token' => $token,
                'limit' => 500,
            ]);
        }

        if ($response->failed()) {
            throw new \Exception(
                'Erreur Weezevent : ' . $response->status() . ' ' . $response->body()
            );
        }

        return $response->json();
    }
}

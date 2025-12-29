<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeezeventService
{
    protected string $baseUrl = 'https://api.weezevent.com';

    public function validateTicket(string $barcode): ?array
    {    
        
        // FAKE DEV
    if (app()->environment('local')) {
        return [
            'participant_id' => 'WZP123',
            'event_id' => 'WZEVENT01',
            'firstname' => 'Jean',
            'lastname' => 'Dupont',
            'email' => 'jean.dupont@test.com',
        ];
    }

        $response = Http::withOptions([
            'verify' => false, // 👈 DEV LOCAL SEULEMENT
        ])->withHeaders([
            'Authorization' => 'Bearer ' . config('services.weezevent.api_key'),
        ])->post($this->baseUrl . '/checkin/scan', [
            'barcode' => $barcode,
        ]);

        if ($response->failed()) {
            return null;
        }

        return $response->json();
    }
}

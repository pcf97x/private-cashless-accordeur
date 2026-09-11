<?php

namespace App\Http\Controllers;

use App\Mail\AccessConfirmed;
use App\Models\Checkin;
use App\Models\Contact;
use App\Services\WeezeventParticipantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AccessController extends Controller
{
    public function create()
    {
        return view('access.form');
    }

    public function store(Request $request, WeezeventParticipantService $weezevent)
    {
        $request->validate([
            'firstname' => 'required|string',
            'lastname'  => 'required|string',
            'email'     => 'nullable|email',
            'company'   => 'nullable|string',
            'purpose'   => 'nullable|string',
        ]);

        // 1. Contact (déduplication par email)
        $contact = null;

        if ($request->email) {
            $contact = Contact::firstOrCreate(
                ['email' => $request->email],
                [
                    'firstname' => $request->firstname,
                    'lastname'  => $request->lastname,
                    'company'   => $request->company,
                ]
            );

            $contact->update([
                'firstname' => $contact->firstname ?: $request->firstname,
                'lastname'  => $contact->lastname  ?: $request->lastname,
                'company'   => $request->company ?? $contact->company,
            ]);

            // 2. Vérifier si un pass existe déjà avec un code Weezevent
            $existingCheckin = Checkin::where('contact_id', $contact->id)
                ->whereNotNull('weez_ticket_code')
                ->first();

            if ($existingCheckin) {
                // Renvoyer le QR code existant par email
                Mail::to($contact->email)->send(new AccessConfirmed($existingCheckin));

                return view('access.success', [
                    'barcode' => $existingCheckin->weez_ticket_code,
                    'existing' => true,
                ]);
            }
        }

        // 3. Créer un nouveau Checkin (pas de pass existant)
        $checkin = Checkin::create([
            'contact_id' => $contact?->id,
            'firstname'  => $request->firstname,
            'lastname'   => $request->lastname,
            'company'    => $request->company,
            'email'      => $request->email,
            'purpose'    => $request->purpose,
            'qr_token'   => (string) Str::uuid(),
        ]);

        // 4. Création Weezevent
        try {
            $response = $weezevent->createParticipant([
                'firstname' => $checkin->firstname,
                'lastname'  => $checkin->lastname,
                'email'     => $checkin->email,
            ]);

            $participant = $response['participants'][0] ?? null;

            if ($participant) {
                $checkin->update([
                    'weez_participant_id' => $participant['id_participant'] ?? null,
                    'weez_ticket_code'    => $participant['barcode_id'] ?? null,
                    'weez_event_id'       => $participant['id_evenement'] ?? null,
                ]);
            }
        } catch (\Exception $e) {
            // Weezevent peut échouer, on continue
        }

        // 5. Envoi email de confirmation avec billet
        if ($checkin->email) {
            Mail::to($checkin->email)->send(new AccessConfirmed($checkin));
        }

        // 6. Retour page succès
        return view('access.success', [
            'barcode' => $checkin->weez_ticket_code ?? $checkin->qr_token,
            'existing' => false,
        ]);
    }
}

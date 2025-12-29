<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Services\WeezeventParticipantService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AccessController extends Controller
{
    public function create()
    {
        return view('access.form');
    }


public function store(Request $request, WeezeventParticipantService $weezevent)
{
    $checkin = Checkin::create([
        'firstname' => $request->firstname,
        'lastname'  => $request->lastname,
        'email'     => $request->email,
        'purpose'   => $request->purpose,
        'qr_token'  => (string) Str::uuid(),
    ]);

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
        ]);
    }

    return view('access.success', [
        'barcode' => $participant['barcode_id'] ?? null
    ]);
}


}

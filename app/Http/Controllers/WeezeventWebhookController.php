<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checkin;
use Illuminate\Support\Facades\Log;

class WeezeventWebhookController extends Controller
{
    public function scan(Request $request)
    {
        // Log brut (debug & preuve)
        Log::info('Weezevent scan webhook', $request->all());

        $barcode = $request->input('barcode');
        $scanType = $request->input('type'); // entry / exit (selon Weezevent)
        $scannedAt = now();

        if (!$barcode) {
            return response()->json(['error' => 'Missing barcode'], 400);
        }

        $checkin = Checkin::where('weez_ticket_code', $barcode)->first();

        if (!$checkin) {
            // On accepte quand même → création si besoin
            $checkin = Checkin::create([
                'weez_ticket_code' => $barcode,
                'checked_in_at' => $scanType === 'entry' ? $scannedAt : null,
                'checked_out_at' => $scanType === 'exit' ? $scannedAt : null,
            ]);
        } else {
            if ($scanType === 'entry') {
                $checkin->update(['checked_in_at' => $scannedAt]);
            }

            if ($scanType === 'exit') {
                $checkin->update(['checked_out_at' => $scannedAt]);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}

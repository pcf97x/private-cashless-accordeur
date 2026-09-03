<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckinController extends Controller
{
    public function index()
    {
        $checkins = Checkin::orderByDesc('created_at')->get();

        return view('admin.checkins.index', [
            'checkins' => $checkins,
        ]);
    }

    /**
     * Scan d'un QR code (par qr_token ou weez_ticket_code)
     * Entrée si pas encore pointé, Sortie si déjà entré
     */
    public function scan(string $token)
    {
        $checkin = Checkin::where('weez_ticket_code', $token)
            ->orWhere('qr_token', $token)
            ->first();

        if (!$checkin) {
            return redirect()->route('checkins.index')
                ->with('error', 'Aucun pass trouvé pour le code : ' . $token);
        }

        // Entrée
        if (is_null($checkin->entry_at)) {
            $checkin->update([
                'entry_at' => now(),
                'scan_date' => now(),
            ]);
        }
        // Sortie
        elseif (is_null($checkin->exit_at)) {
            $checkin->update([
                'exit_at' => now(),
            ]);
        }

        return view('admin.checkins.scan', compact('checkin'));
    }

    /**
     * Scan via code Weezevent (POST depuis formulaire de scan)
     */
    public function scanWeezevent(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = trim($request->code);

        $checkin = Checkin::where('weez_ticket_code', $code)
            ->orWhere('qr_token', $code)
            ->first();

        if (!$checkin) {
            return redirect()->route('checkins.index')
                ->with('error', 'Aucun pass trouvé pour le code : ' . $code);
        }

        // Entrée
        if (is_null($checkin->entry_at)) {
            $checkin->update([
                'entry_at' => now(),
                'scan_date' => now(),
            ]);
        }
        // Sortie
        elseif (is_null($checkin->exit_at)) {
            $checkin->update([
                'exit_at' => now(),
            ]);
        }

        return view('admin.checkins.scan', compact('checkin'));
    }

    public function edit(string $code)
    {
        $checkin = Checkin::where('weez_ticket_code', $code)
            ->orWhere('qr_token', $code)
            ->firstOrFail();

        return view('admin.checkins.edit', compact('checkin'));
    }

    public function update(Request $request, string $code)
    {
        $checkin = Checkin::where('weez_ticket_code', $code)
            ->orWhere('qr_token', $code)
            ->firstOrFail();

        $request->validate([
            'firstname' => 'nullable|string|max:255',
            'lastname'  => 'nullable|string|max:255',
            'company'   => 'nullable|string|max:255',
            'email'     => 'nullable|email|max:255',
            'purpose'   => 'nullable|string|max:255',
        ]);

        $checkin->update($request->only(['firstname', 'lastname', 'company', 'email', 'purpose']));

        return redirect()->route('checkins.index')
            ->with('success', 'Visiteur mis à jour.');
    }
}

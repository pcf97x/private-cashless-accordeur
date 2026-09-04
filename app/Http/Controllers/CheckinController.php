<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckinController extends Controller
{
    public function index()
    {
        $checkins = Checkin::orderByDesc('scan_date')->orderByDesc('created_at')->get();

        return view('admin.checkins.index', [
            'checkins' => $checkins,
        ]);
    }

    /**
     * Scan d'un QR code via URL (par qr_token ou weez_ticket_code)
     */
    public function scan(string $token)
    {
        $originalCheckin = Checkin::where('weez_ticket_code', $token)
            ->orWhere('qr_token', $token)
            ->first();

        if (!$originalCheckin) {
            return redirect()->route('checkins.index')
                ->with('error', 'Aucun pass trouvé pour le code : ' . $token);
        }

        $name = trim(($originalCheckin->firstname ?? '') . ' ' . ($originalCheckin->lastname ?? ''));
        $today = now()->toDateString();

        $todayCheckin = Checkin::where(function ($q) use ($token) {
                $q->where('weez_ticket_code', $token)->orWhere('qr_token', $token);
            })
            ->whereDate('scan_date', $today)
            ->first();

        if (!$todayCheckin) {
            if (is_null($originalCheckin->entry_at) && is_null($originalCheckin->scan_date)) {
                $originalCheckin->update(['entry_at' => now(), 'scan_date' => now()]);
            } else {
                Checkin::create([
                    'contact_id' => $originalCheckin->contact_id,
                    'firstname' => $originalCheckin->firstname,
                    'lastname' => $originalCheckin->lastname,
                    'company' => $originalCheckin->company,
                    'email' => $originalCheckin->email,
                    'purpose' => $originalCheckin->purpose,
                    'qr_token' => $originalCheckin->qr_token,
                    'weez_ticket_code' => $originalCheckin->weez_ticket_code,
                    'weez_event_id' => $originalCheckin->weez_event_id,
                    'weez_participant_id' => $originalCheckin->weez_participant_id,
                    'entry_at' => now(),
                    'scan_date' => now(),
                ]);
            }
            return redirect()->route('checkins.index')
                ->with('success', 'Entrée enregistrée pour ' . $name);
        }

        // Scan suivant → met à jour la sortie
        $todayCheckin->update(['exit_at' => now()]);
        return redirect()->route('checkins.index')
            ->with('success', 'Sortie mise à jour pour ' . $name . ' à ' . now()->format('H:i'));
    }

    /**
     * Scan via code Weezevent (POST depuis formulaire de scan)
     * Règle : premier scan du jour = entrée, chaque scan suivant = met à jour la sortie
     */
    public function scanWeezevent(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = trim($request->code);

        // Trouver le pass original (pour les infos du visiteur)
        $originalCheckin = Checkin::where('weez_ticket_code', $code)
            ->orWhere('qr_token', $code)
            ->first();

        if (!$originalCheckin) {
            return redirect()->route('checkins.index')
                ->with('error', 'Aucun pass trouvé pour le code : ' . $code);
        }

        $name = trim(($originalCheckin->firstname ?? '') . ' ' . ($originalCheckin->lastname ?? ''));
        $today = now()->toDateString();

        // Chercher un pointage du jour pour ce code
        $todayCheckin = Checkin::where(function ($q) use ($code) {
                $q->where('weez_ticket_code', $code)->orWhere('qr_token', $code);
            })
            ->whereDate('scan_date', $today)
            ->first();

        if (!$todayCheckin) {
            // Premier scan du jour → Entrée
            if (is_null($originalCheckin->entry_at) && is_null($originalCheckin->scan_date)) {
                $originalCheckin->update([
                    'entry_at' => now(),
                    'scan_date' => now(),
                ]);
            } else {
                Checkin::create([
                    'contact_id' => $originalCheckin->contact_id,
                    'firstname' => $originalCheckin->firstname,
                    'lastname' => $originalCheckin->lastname,
                    'company' => $originalCheckin->company,
                    'email' => $originalCheckin->email,
                    'purpose' => $originalCheckin->purpose,
                    'qr_token' => $originalCheckin->qr_token,
                    'weez_ticket_code' => $originalCheckin->weez_ticket_code,
                    'weez_event_id' => $originalCheckin->weez_event_id,
                    'weez_participant_id' => $originalCheckin->weez_participant_id,
                    'entry_at' => now(),
                    'scan_date' => now(),
                ]);
            }

            return redirect()->route('checkins.index')
                ->with('success', 'Entrée enregistrée pour ' . $name);
        }

        // Scan suivant → met à jour l'heure de sortie (le dernier scan de la journée sera la sortie)
        $todayCheckin->update(['exit_at' => now()]);
        return redirect()->route('checkins.index')
            ->with('success', 'Sortie mise à jour pour ' . $name . ' à ' . now()->format('H:i'));
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

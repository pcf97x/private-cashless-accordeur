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
     * Crée une nouvelle entrée à partir d'un checkin existant
     */
    private function createNewEntry(Checkin $original): Checkin
    {
        return Checkin::create([
            'contact_id' => $original->contact_id,
            'firstname' => $original->firstname,
            'lastname' => $original->lastname,
            'company' => $original->company,
            'email' => $original->email,
            'purpose' => $original->purpose,
            'qr_token' => $original->qr_token,
            'weez_ticket_code' => $original->weez_ticket_code,
            'weez_event_id' => $original->weez_event_id,
            'weez_participant_id' => $original->weez_participant_id,
            'entry_at' => now(),
            'scan_date' => now(),
        ]);
    }

    /**
     * Logique commune : 1 ligne par jour, premier scan = entrée, dernier scan = sortie
     */
    private function handleScan(Checkin $originalCheckin): array
    {
        $name = trim(($originalCheckin->firstname ?? '') . ' ' . ($originalCheckin->lastname ?? ''));
        $today = now()->toDateString();
        $code = $originalCheckin->weez_ticket_code ?? $originalCheckin->qr_token;

        // Chercher le pointage du jour pour ce code
        $todayCheckin = Checkin::where(function ($q) use ($code) {
                $q->where('weez_ticket_code', $code)->orWhere('qr_token', $code);
            })
            ->whereDate('scan_date', $today)
            ->first();

        if (!$todayCheckin) {
            // Pas encore pointé aujourd'hui → Entrée
            if (is_null($originalCheckin->entry_at) && is_null($originalCheckin->scan_date)) {
                $originalCheckin->update(['entry_at' => now(), 'scan_date' => now()]);
            } else {
                $this->createNewEntry($originalCheckin);
            }
            return ['type' => 'success', 'message' => 'Entrée enregistrée pour ' . $name];
        }

        // Déjà pointé aujourd'hui → mettre à jour la sortie
        $todayCheckin->update(['exit_at' => now()]);
        return ['type' => 'success', 'message' => 'Sortie enregistrée pour ' . $name . ' à ' . now()->format('H:i')];
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

        $result = $this->handleScan($originalCheckin);
        return redirect()->route('checkins.index')->with($result['type'], $result['message']);
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

        $originalCheckin = Checkin::where('weez_ticket_code', $code)
            ->orWhere('qr_token', $code)
            ->first();

        if (!$originalCheckin) {
            return redirect()->route('checkins.index')
                ->with('error', 'Aucun pass trouvé pour le code : ' . $code);
        }

        $result = $this->handleScan($originalCheckin);
        return redirect()->route('checkins.index')->with($result['type'], $result['message']);
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

<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use Illuminate\Http\Request;


class CheckinController extends Controller
{
public function index()
{
    $scans = \DB::table('checkins as s')
        ->leftJoin('checkins as p', function ($join) {
            $join->on('s.weez_ticket_code', '=', 'p.weez_ticket_code')
                 ->whereNotNull('p.firstname');
        })
        ->whereNotNull('s.scan_date')
        ->orderByDesc('s.entry_at')
        ->select([
            's.weez_ticket_code',
            's.scan_date',
            's.entry_at',
            's.exit_at',
            'p.firstname',
            'p.lastname',
            'p.company',
            'p.email',
            'p.purpose',
        ])
        ->get();

    return view('admin.checkins.index', [
        'scans' => $scans
    ]);
}
public function edit(string $code)
{
    $checkin = Checkin::where('weez_ticket_code', $code)
        ->whereNotNull('scan_date')
        ->orderByDesc('entry_at')
        ->firstOrFail();

    return view('admin.checkins.edit', compact('checkin'));
}

public function update(Request $request, string $code)
{
    $request->validate([
        'firstname' => 'nullable|string|max:191',
        'lastname'  => 'nullable|string|max:191',
        'company'   => 'nullable|string|max:191',
        'email'     => 'nullable|email|max:191',
        'purpose'   => 'nullable|string|max:191',
    ]);

    Checkin::where('weez_ticket_code', $code)
        ->whereNotNull('scan_date')
        ->update($request->only([
            'firstname',
            'lastname',
            'company',
            'email',
            'purpose',
        ]));

    return redirect()
        ->route('checkins.index')
        ->with('success', 'Visiteur mis à jour avec succès');
}



}

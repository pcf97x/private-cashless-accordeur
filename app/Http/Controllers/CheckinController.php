<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class CheckinController extends Controller
{
    public function index()
    {
        $scans = DB::table('checkins as s')
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
}

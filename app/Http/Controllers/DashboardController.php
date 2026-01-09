<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Checkin;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'contactsCount' => Contact::count(),
            'presentCount'  => Checkin::whereNull('exit_at')->count(),
            'todayCount'    => Checkin::whereDate('entry_at', Carbon::today())->count(),
            'lastCheckins'  => Checkin::with('contact')
                                ->orderByDesc('entry_at')
                                ->limit(5)
                                ->get(),
        ]);
    }
}

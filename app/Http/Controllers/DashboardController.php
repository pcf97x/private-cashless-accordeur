<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\Contact;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'contactsCount' => Contact::count(),
            'todayEntries'  => Checkin::whereDate('entry_at', today())->count(),
            'presentNow'    => Checkin::whereNotNull('entry_at')
                                      ->whereNull('exit_at')
                                      ->count(),
        ]);
    }
}

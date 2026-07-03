<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
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
}

<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function create()
    {
        return view('checkins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
            'lastname'  => 'required',
            'purpose'   => 'required',
        ]);

        $checkin = Checkin::create([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
            'purpose'   => $request->purpose,
            'qr_token'  => Str::uuid(),
        ]);

        return view('checkins.confirm', compact('checkin'));
    }

    public function index()
    {
        $checkins = Checkin::latest()->get();
        return view('admin.checkins.index', compact('checkins'));
    }

    public function scan($token)
    {
        $checkin = Checkin::where('qr_token', $token)->firstOrFail();

        if (!$checkin->checked_in_at) {
            $checkin->update(['checked_in_at' => now()]);
        }

        return view('admin.checkins.scan', compact('checkin'));
    }
}

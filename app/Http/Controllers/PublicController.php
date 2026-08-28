<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use App\Models\TimeSlot;
use App\Models\EcosystemPartner;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        return view('public.home');
    }

    public function espaces()
    {
        $rooms = Room::orderBy('capacity')->get();
        return view('public.espaces', compact('rooms'));
    }

    public function planning()
    {
        $rooms = Room::orderBy('name')->get();
        $reservations = Reservation::with(['room', 'timeSlot'])
            ->where('status', '!=', 'cancelled')
            ->where('date', '>=', now()->subMonths(1)->startOfMonth())
            ->get();
        $timeSlots = TimeSlot::where('active', true)->orderBy('order_index')->get();

        return view('public.planning', compact('rooms', 'reservations', 'timeSlots'));
    }

    public function ecosysteme()
    {
        $partners = EcosystemPartner::where('active', true)->orderBy('sort_order')->orderBy('name')->get();
        return view('public.ecosysteme', compact('partners'));
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function contactStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // For now, just redirect with success. Later: send email or store in DB.
        return back()->with('success', 'Votre message a bien été envoyé. Nous vous répondrons dans les meilleurs délais.');
    }
}

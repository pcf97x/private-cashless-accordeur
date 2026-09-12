<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\TimeSlot;
use App\Models\PricingProfile;
use App\Models\RoomRate;
use Illuminate\Http\Request;

class RoomRateController extends Controller
{
    public function index()
    {
        return view('admin.rates.index', [
            'rooms' => Room::where('active', true)->get(),
            'timeSlots' => TimeSlot::where('active', true)
                ->orderBy('order_index')
                ->get(),
            'profiles' => PricingProfile::where('active', true)->get(),

            // clé simple et ultra efficace
            'rates' => RoomRate::all()->keyBy(function ($rate) {
                return $rate->room_id . '_' . $rate->time_slot_id . '_' . $rate->pricing_profile_id;
            }),
        ]);
    }

    public function store(Request $request)
    {
        foreach ($request->rates as $roomId => $slots) {
            foreach ($slots as $slotId => $profiles) {
                foreach ($profiles as $profileId => $price) {

                    if ($price === null || $price === '') {
                        continue;
                    }

                    RoomRate::updateOrCreate(
                        [
                            'room_id' => $roomId,
                            'time_slot_id' => $slotId,
                            'pricing_profile_id' => $profileId,
                        ],
                        [
                            'price' => $price,
                        ]
                    );
                }
            }
        }

        return back()->with('success', 'Tarifs enregistrés');
    }
}

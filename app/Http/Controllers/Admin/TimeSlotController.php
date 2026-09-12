<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

class TimeSlotController extends Controller
{
    public function index()
    {
        $timeSlots = TimeSlot::orderBy('order_index')->get();
        return view('admin.time_slots.index', compact('timeSlots'));
    }

    public function create()
    {
        return view('admin.time_slots.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:time_slots,code',
            'label' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'order_index' => 'nullable|integer',
            'active' => 'boolean',
        ]);

        TimeSlot::create($data);

        return redirect()->route('admin.time-slots.index')
            ->with('success', 'Créneau créé avec succès');
    }

    public function edit(TimeSlot $time_slot)
    {
        return view('admin.time_slots.edit', compact('time_slot'));
    }

    public function update(Request $request, TimeSlot $time_slot)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:time_slots,code,' . $time_slot->id,
            'label' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'order_index' => 'nullable|integer',
            'active' => 'boolean',
        ]);

        $time_slot->update($data);

        return redirect()->route('admin.time-slots.index')
            ->with('success', 'Créneau mis à jour');
    }

    public function destroy(TimeSlot $time_slot)
    {
        // ⚠️ On ne supprime PAS si utilisé
        if ($time_slot->reservations()->exists()) {
            $time_slot->update(['active' => false]);
            return back()->with('warning', 'Créneau désactivé (réservations existantes)');
        }

        $time_slot->delete();
        return back()->with('success', 'Créneau supprimé');
    }
}

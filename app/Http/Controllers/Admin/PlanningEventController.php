<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlanningEvent;
use App\Models\Room;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

class PlanningEventController extends Controller
{
    public function index()
    {
        $events = PlanningEvent::with('room', 'timeSlot')->orderByDesc('date')->get();
        return view('admin.planning-events.index', compact('events'));
    }

    public function create()
    {
        $rooms = Room::where('active', true)->orderBy('name')->get();
        $timeSlots = TimeSlot::where('active', true)->orderBy('order_index')->get();
        return view('admin.planning-events.create', compact('rooms', 'timeSlots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'color' => 'required|string|max:7',
            'room_id' => 'nullable|exists:rooms,id',
            'time_slot_id' => 'nullable|exists:time_slots,id',
            'visibility' => 'required|in:public,private',
        ]);

        PlanningEvent::create([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'color' => $request->color,
            'active' => $request->boolean('active', true),
            'room_id' => $request->room_id ?: null,
            'time_slot_id' => $request->time_slot_id ?: null,
            'visibility' => $request->visibility,
        ]);

        return redirect()->route('admin.planning-events.index')
            ->with('success', 'Evenement cree avec succes.');
    }

    public function edit(PlanningEvent $planning_event)
    {
        $rooms = Room::where('active', true)->orderBy('name')->get();
        $timeSlots = TimeSlot::where('active', true)->orderBy('order_index')->get();
        return view('admin.planning-events.edit', ['event' => $planning_event, 'rooms' => $rooms, 'timeSlots' => $timeSlots]);
    }

    public function update(Request $request, PlanningEvent $planning_event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'color' => 'required|string|max:7',
            'room_id' => 'nullable|exists:rooms,id',
            'time_slot_id' => 'nullable|exists:time_slots,id',
            'visibility' => 'required|in:public,private',
        ]);

        $planning_event->update([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'color' => $request->color,
            'active' => $request->boolean('active'),
            'room_id' => $request->room_id ?: null,
            'time_slot_id' => $request->time_slot_id ?: null,
            'visibility' => $request->visibility,
        ]);

        return redirect()->route('admin.planning-events.index')
            ->with('success', 'Evenement mis a jour.');
    }

    public function destroy(PlanningEvent $planning_event)
    {
        $planning_event->delete();

        return redirect()->route('admin.planning-events.index')
            ->with('success', 'Evenement supprime.');
    }
}

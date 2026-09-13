<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlanningEvent;
use Illuminate\Http\Request;

class PlanningEventController extends Controller
{
    public function index()
    {
        $events = PlanningEvent::orderByDesc('date')->get();
        return view('admin.planning-events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.planning-events.create');
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
        ]);

        PlanningEvent::create([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'color' => $request->color,
            'active' => $request->boolean('active', true),
        ]);

        return redirect()->route('admin.planning-events.index')
            ->with('success', 'Evenement cree avec succes.');
    }

    public function edit(PlanningEvent $planning_event)
    {
        return view('admin.planning-events.edit', ['event' => $planning_event]);
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
        ]);

        $planning_event->update([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'color' => $request->color,
            'active' => $request->boolean('active'),
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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::orderBy('name')->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'capacity'    => 'required|integer|min:1',
            'surface_m2'  => 'nullable|numeric|min:0',
            'equipments'  => 'nullable|string',
            'description' => 'nullable|string',
            'location'    => 'nullable|string|max:255',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data = [
            'name'        => $request->name,
            'capacity'    => $request->capacity,
            'surface_m2'  => $request->surface_m2,
            'equipments'  => $request->equipments,
            'description' => $request->description,
            'location'    => $request->location,
            'active'      => $request->boolean('active', true),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('rooms', 'public');
        }

        Room::create($data);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Salle créée avec succès.');
    }

    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'capacity'    => 'required|integer|min:1',
            'surface_m2'  => 'nullable|numeric|min:0',
            'equipments'  => 'nullable|string',
            'description' => 'nullable|string',
            'location'    => 'nullable|string|max:255',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data = [
            'name'        => $request->name,
            'capacity'    => $request->capacity,
            'surface_m2'  => $request->surface_m2,
            'equipments'  => $request->equipments,
            'description' => $request->description,
            'location'    => $request->location,
            'active'      => $request->boolean('active'),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('rooms', 'public');
        }

        $room->update($data);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Salle mise à jour.');
    }

    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Salle supprimée.');
    }
}

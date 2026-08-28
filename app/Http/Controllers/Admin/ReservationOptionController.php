<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReservationOption;
use Illuminate\Http\Request;

class ReservationOptionController extends Controller
{
    public function index()
    {
        $options = ReservationOption::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.options.index', compact('options'));
    }

    public function create()
    {
        return view('admin.options.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
        ]);

        ReservationOption::create([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'active'      => $request->boolean('active', true),
            'sort_order'  => $request->integer('sort_order', 0),
        ]);

        return redirect()->route('admin.options.index')
            ->with('success', 'Option créée avec succès.');
    }

    public function edit(ReservationOption $option)
    {
        return view('admin.options.edit', compact('option'));
    }

    public function update(Request $request, ReservationOption $option)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
        ]);

        $option->update([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'active'      => $request->boolean('active'),
            'sort_order'  => $request->integer('sort_order', 0),
        ]);

        return redirect()->route('admin.options.index')
            ->with('success', 'Option mise à jour.');
    }

    public function destroy(ReservationOption $option)
    {
        $option->delete();

        return redirect()->route('admin.options.index')
            ->with('success', 'Option supprimée.');
    }
}

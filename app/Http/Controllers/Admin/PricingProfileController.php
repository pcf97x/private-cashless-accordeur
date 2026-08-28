<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricingProfile;
use Illuminate\Http\Request;

class PricingProfileController extends Controller
{
    public function index()
    {
        $profiles = PricingProfile::orderBy('id')->get();
        return view('admin.pricing_profiles.index', compact('profiles'));
    }

    public function create()
    {
        return view('admin.pricing_profiles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'code'  => 'required|string|max:50|unique:pricing_profiles,code',
        ]);

        PricingProfile::create([
            'code'   => strtoupper($request->code),
            'label'  => $request->label,
            'active' => $request->boolean('active', true),
        ]);

        return redirect()->route('admin.pricing-profiles.index')
            ->with('success', 'Profil tarifaire créé avec succès.');
    }

    public function edit(PricingProfile $pricing_profile)
    {
        return view('admin.pricing_profiles.edit', ['profile' => $pricing_profile]);
    }

    public function update(Request $request, PricingProfile $pricing_profile)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'code'  => 'required|string|max:50|unique:pricing_profiles,code,' . $pricing_profile->id,
        ]);

        $pricing_profile->update([
            'code'   => strtoupper($request->code),
            'label'  => $request->label,
            'active' => $request->boolean('active'),
        ]);

        return redirect()->route('admin.pricing-profiles.index')
            ->with('success', 'Profil tarifaire mis à jour.');
    }

    public function destroy(PricingProfile $pricing_profile)
    {
        $pricing_profile->delete();

        return redirect()->route('admin.pricing-profiles.index')
            ->with('success', 'Profil tarifaire supprimé.');
    }
}

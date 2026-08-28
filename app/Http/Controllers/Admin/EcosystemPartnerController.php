<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EcosystemPartner;
use Illuminate\Http\Request;

class EcosystemPartnerController extends Controller
{
    public function index()
    {
        $partners = EcosystemPartner::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.ecosystem.index', compact('partners'));
    }

    public function create()
    {
        return view('admin.ecosystem.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'contact_name'  => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'website'       => 'nullable|url|max:255',
            'logo'          => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'contact_name', 'contact_email', 'contact_phone', 'website']);
        $data['active'] = $request->boolean('active', true);
        $data['sort_order'] = $request->integer('sort_order', 0);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('ecosystem', 'public');
        }

        EcosystemPartner::create($data);

        return redirect()->route('admin.ecosystem.index')
            ->with('success', 'Partenaire ajouté avec succès.');
    }

    public function edit(EcosystemPartner $ecosystem)
    {
        return view('admin.ecosystem.edit', ['partner' => $ecosystem]);
    }

    public function update(Request $request, EcosystemPartner $ecosystem)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'contact_name'  => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'website'       => 'nullable|url|max:255',
            'logo'          => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'contact_name', 'contact_email', 'contact_phone', 'website']);
        $data['active'] = $request->boolean('active');
        $data['sort_order'] = $request->integer('sort_order', 0);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('ecosystem', 'public');
        }

        $ecosystem->update($data);

        return redirect()->route('admin.ecosystem.index')
            ->with('success', 'Partenaire mis à jour.');
    }

    public function destroy(EcosystemPartner $ecosystem)
    {
        $ecosystem->delete();

        return redirect()->route('admin.ecosystem.index')
            ->with('success', 'Partenaire supprimé.');
    }
}

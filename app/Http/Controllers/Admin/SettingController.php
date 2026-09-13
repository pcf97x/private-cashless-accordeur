<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'conciergerie_email' => Setting::get('conciergerie_email', 'laconciergerie@groupe-aprosep.com'),
            'admin_email' => Setting::get('admin_email', 'contact@privatecashless.com'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'conciergerie_email' => 'required|string|max:500',
            'admin_email' => 'required|string|max:500',
        ]);

        Setting::set('conciergerie_email', $request->conciergerie_email);
        Setting::set('admin_email', $request->admin_email);

        return back()->with('success', 'Parametres enregistres.');
    }
}

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
            'invoice_company_name' => Setting::get('invoice_company_name', "L'Accordeur - Pole Associatif de Guyane"),
            'invoice_company_address' => Setting::get('invoice_company_address', ''),
            'invoice_company_siret' => Setting::get('invoice_company_siret', ''),
            'invoice_company_phone' => Setting::get('invoice_company_phone', ''),
            'invoice_company_email' => Setting::get('invoice_company_email', ''),
            'invoice_payment_info' => Setting::get('invoice_payment_info', ''),
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

        // Invoice settings
        foreach (['invoice_company_name', 'invoice_company_address', 'invoice_company_siret', 'invoice_company_phone', 'invoice_company_email', 'invoice_payment_info'] as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }

        return back()->with('success', 'Parametres enregistres.');
    }
}

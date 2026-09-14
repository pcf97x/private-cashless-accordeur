@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="page-header">
        <h1>Parametres</h1>
        <p>Configuration des notifications et emails</p>
    </div>

    <div class="card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf

            <div>
                <label for="conciergerie_email" class="form-label">Email conciergerie</label>
                <input type="text" name="conciergerie_email" id="conciergerie_email" required class="form-input" value="{{ old('conciergerie_email', $settings['conciergerie_email']) }}">
                <p class="text-xs text-gray-400 mt-1">Recoit toutes les notifications de reservation. Plusieurs adresses separees par une virgule.</p>
                @error('conciergerie_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="admin_email" class="form-label">Email administration</label>
                <input type="text" name="admin_email" id="admin_email" required class="form-input" value="{{ old('admin_email', $settings['admin_email']) }}">
                <p class="text-xs text-gray-400 mt-1">Adresse de secours / copie. Plusieurs adresses separees par une virgule.</p>
                @error('admin_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <hr class="border-gray-100">

            <h3 class="text-sm font-bold text-gray-700">Facturation — Infos entreprise (PDF)</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Nom de l'entreprise</label>
                    <input type="text" name="invoice_company_name" class="form-input" value="{{ old('invoice_company_name', $settings['invoice_company_name']) }}">
                </div>
                <div>
                    <label class="form-label">SIRET</label>
                    <input type="text" name="invoice_company_siret" class="form-input" value="{{ old('invoice_company_siret', $settings['invoice_company_siret']) }}">
                </div>
            </div>

            <div>
                <label class="form-label">Adresse</label>
                <textarea name="invoice_company_address" rows="2" class="form-input">{{ old('invoice_company_address', $settings['invoice_company_address']) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Telephone</label>
                    <input type="text" name="invoice_company_phone" class="form-input" value="{{ old('invoice_company_phone', $settings['invoice_company_phone']) }}">
                </div>
                <div>
                    <label class="form-label">Email facturation</label>
                    <input type="text" name="invoice_company_email" class="form-input" value="{{ old('invoice_company_email', $settings['invoice_company_email']) }}">
                </div>
            </div>

            <div>
                <label class="form-label">Informations de paiement (RIB, IBAN...)</label>
                <textarea name="invoice_payment_info" rows="3" class="form-input" placeholder="IBAN : FR76 ...&#10;BIC : ...&#10;Banque : ...">{{ old('invoice_payment_info', $settings['invoice_payment_info']) }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Affiche en bas de la facture PDF.</p>
            </div>

            <div class="pt-4 border-t border-gray-100">
                <button type="submit" class="btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>

</div>
@endsection

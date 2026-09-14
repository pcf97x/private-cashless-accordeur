@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">

    <a href="{{ route('admin.invoices.show', $invoice) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-accordeur-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour a la facture {{ $invoice->invoice_number }}
    </a>

    <div class="page-header">
        <h1>Modifier la facture {{ $invoice->invoice_number }}</h1>
    </div>

    <div class="card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.invoices.update', $invoice) }}" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Structure / Client</label>
                    <input type="text" name="client_name" required class="form-input" value="{{ old('client_name', $invoice->client_name) }}">
                </div>
                <div>
                    <label class="form-label">Programme</label>
                    <input type="text" name="programme" class="form-input" value="{{ old('programme', $invoice->programme) }}">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Email</label>
                    <input type="text" name="client_email" class="form-input" value="{{ old('client_email', $invoice->client_email) }}">
                </div>
                <div>
                    <label class="form-label">Telephone</label>
                    <input type="text" name="client_phone" class="form-input" value="{{ old('client_phone', $invoice->client_phone) }}">
                </div>
            </div>

            <div>
                <label class="form-label">Adresse</label>
                <textarea name="client_address" rows="2" class="form-input">{{ old('client_address', $invoice->client_address) }}</textarea>
            </div>

            <div>
                <label class="form-label">Reference Chorus</label>
                <input type="text" name="chorus_reference" class="form-input" value="{{ old('chorus_reference', $invoice->chorus_reference) }}">
            </div>

            <div>
                <label class="form-label">Notes internes</label>
                <textarea name="notes" rows="2" class="form-input">{{ old('notes', $invoice->notes) }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="{{ route('admin.invoices.show', $invoice) }}" class="btn-ghost">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">

    <a href="{{ route('admin.invoices.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-accordeur-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour aux factures
    </a>

    <div class="page-header">
        <h1>Nouvelle facture</h1>
        <p>Creer une facture a partir de reservations</p>
    </div>

    <div class="card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.invoices.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Structure / Client</label>
                    <input type="text" name="client_name" required class="form-input" placeholder="DPJJ, GUYACOOP..." value="{{ old('client_name', $reservations->first()->name ?? '') }}">
                </div>
                <div>
                    <label class="form-label">Programme</label>
                    <input type="text" name="programme" class="form-input" placeholder="Concours, Reunion, Formation..." value="{{ old('programme', $reservations->first()->event_name ?? '') }}">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Email</label>
                    <input type="text" name="client_email" class="form-input" value="{{ old('client_email', $reservations->first()->email ?? '') }}">
                </div>
                <div>
                    <label class="form-label">Telephone</label>
                    <input type="text" name="client_phone" class="form-input" value="{{ old('client_phone', $reservations->first()->phone ?? '') }}">
                </div>
            </div>

            <div>
                <label class="form-label">Adresse (pour la facture)</label>
                <textarea name="client_address" rows="2" class="form-input" placeholder="Adresse postale du client...">{{ old('client_address') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Reference Chorus</label>
                    <input type="text" name="chorus_reference" class="form-input" placeholder="N° engagement juridique..." value="{{ old('chorus_reference') }}">
                </div>
            </div>

            <div>
                <label class="form-label">Notes internes</label>
                <textarea name="notes" rows="2" class="form-input" placeholder="Notes visibles uniquement en interne...">{{ old('notes') }}</textarea>
            </div>

            <hr class="border-gray-100">

            {{-- Reservation picker --}}
            <div>
                <label class="form-label">Reservations a facturer</label>
                <div class="space-y-2 max-h-64 overflow-y-auto border border-gray-200 rounded-xl p-3">
                    @foreach($allReservations as $res)
                        <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="reservation_ids[]" value="{{ $res->id }}"
                                {{ in_array($res->id, $reservations->pluck('id')->toArray()) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-accordeur-500 focus:ring-accordeur-500">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">{{ $res->room->name ?? '?' }}</span>
                                <span class="text-xs text-gray-500">— {{ $res->date->format('d/m/Y') }} {{ $res->start_at->format('H:i') }}-{{ $res->end_at->format('H:i') }}</span>
                                <span class="text-xs text-gray-400">— {{ $res->name }}</span>
                            </div>
                            <span class="text-sm font-semibold text-accordeur-600">{{ number_format($res->price - $res->discount_amount, 2, ',', ' ') }} &euro;</span>
                        </label>
                    @endforeach
                </div>
                <p class="text-xs text-gray-400 mt-1">Les lignes de facturation seront generees automatiquement (salle, supplements, options).</p>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn-primary">Creer la facture</button>
                <a href="{{ route('admin.invoices.index') }}" class="btn-ghost">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection

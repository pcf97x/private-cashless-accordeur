@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">

    <a href="{{ route('admin.pricing-profiles.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-accordeur-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour aux profils
    </a>

    <div class="page-header">
        <h1>Nouveau profil tarifaire</h1>
        <p>Ajoutez un type de client pour la grille tarifaire</p>
    </div>

    <div class="card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.pricing-profiles.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="label" class="form-label">Libellé</label>
                <input type="text" name="label" id="label" required class="form-input" placeholder="Ex: Résident" value="{{ old('label') }}">
                @error('label') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="code" class="form-label">Code <span class="text-gray-400 font-normal">(identifiant unique, en majuscules)</span></label>
                <input type="text" name="code" id="code" required class="form-input font-mono uppercase" placeholder="Ex: RESIDENT" value="{{ old('code') }}">
                @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="active" id="active" value="1" checked class="rounded border-gray-300 text-accordeur-500 focus:ring-accordeur-500">
                <label for="active" class="text-sm font-medium text-gray-700">Actif (visible lors de la réservation)</label>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn-primary">Créer le profil</button>
                <a href="{{ route('admin.pricing-profiles.index') }}" class="btn-ghost">Annuler</a>
            </div>
        </form>
    </div>

</div>
@endsection

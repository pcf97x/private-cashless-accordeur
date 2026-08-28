@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">

    <a href="{{ route('admin.options.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-accordeur-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour aux options
    </a>

    <div class="page-header">
        <h1>Nouvelle option</h1>
        <p>Ajoutez un extra proposé lors de la réservation</p>
    </div>

    <div class="card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.options.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="form-label">Nom de l'option</label>
                <input type="text" name="name" id="name" required class="form-input" placeholder="Ex: Petit déjeuner" value="{{ old('name') }}">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="form-label">Description <span class="text-gray-400 font-normal">optionnel</span></label>
                <textarea name="description" id="description" rows="2" class="form-input" placeholder="Café, jus, viennoiseries...">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="price" class="form-label">Prix (EUR)</label>
                    <input type="number" name="price" id="price" required step="0.01" min="0" class="form-input" placeholder="15.00" value="{{ old('price') }}">
                    @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="sort_order" class="form-label">Ordre d'affichage</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-input" placeholder="0" value="{{ old('sort_order', 0) }}">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="active" id="active" value="1" checked class="rounded border-gray-300 text-accordeur-500 focus:ring-accordeur-500">
                <label for="active" class="text-sm font-medium text-gray-700">Active (proposée lors de la réservation)</label>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn-primary">Créer l'option</button>
                <a href="{{ route('admin.options.index') }}" class="btn-ghost">Annuler</a>
            </div>
        </form>
    </div>

</div>
@endsection

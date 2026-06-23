@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">

    <a href="{{ route('admin.rooms.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-accordeur-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour aux salles
    </a>

    <div class="page-header">
        <h1>Nouvelle salle</h1>
        <p>Ajoutez un espace à L'Accordeur</p>
    </div>

    <div class="card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.rooms.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="form-label">Nom de la salle</label>
                <input type="text" name="name" id="name" required class="form-input" placeholder="Ex: Salle Oyapock" value="{{ old('name') }}">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="form-label">Description <span class="text-gray-400 font-normal">optionnel</span></label>
                <textarea name="description" id="description" rows="3" class="form-input" placeholder="Salle lumineuse avec vue sur le jardin...">{{ old('description') }}</textarea>
            </div>

            <div>
                <label for="image" class="form-label">Photo de la salle</label>
                <input type="file" name="image" id="image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-accordeur-50 file:text-accordeur-600 hover:file:bg-accordeur-100 file:cursor-pointer file:transition-colors">
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="capacity" class="form-label">Capacité</label>
                    <input type="number" name="capacity" id="capacity" required min="1" class="form-input" placeholder="8" value="{{ old('capacity') }}">
                    @error('capacity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="surface_m2" class="form-label">Surface (m&sup2;) <span class="text-gray-400 font-normal">optionnel</span></label>
                    <input type="number" name="surface_m2" id="surface_m2" step="0.1" min="0" class="form-input" placeholder="25" value="{{ old('surface_m2') }}">
                </div>
            </div>

            <div>
                <label for="location" class="form-label">Adresse <span class="text-gray-400 font-normal">optionnel</span></label>
                <input type="text" name="location" id="location" class="form-input" placeholder="L'Accordeur - 1, rue Roland BARRAT - Cayenne" value="{{ old('location') }}">
            </div>

            <div>
                <label for="equipments" class="form-label">Équipements <span class="text-gray-400 font-normal">optionnel</span></label>
                <input type="text" name="equipments" id="equipments" class="form-input" placeholder="Vidéoprojecteur, Wi-Fi, Tableau blanc" value="{{ old('equipments') }}">
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="active" id="active" value="1" checked class="rounded border-gray-300 text-accordeur-500 focus:ring-accordeur-500">
                <label for="active" class="text-sm font-medium text-gray-700">Salle active (disponible à la réservation)</label>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn-primary">Créer la salle</button>
                <a href="{{ route('admin.rooms.index') }}" class="btn-ghost">Annuler</a>
            </div>
        </form>
    </div>

</div>
@endsection

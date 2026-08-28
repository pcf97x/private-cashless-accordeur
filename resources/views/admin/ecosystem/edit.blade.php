@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">

    <a href="{{ route('admin.ecosystem.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-accordeur-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour à l'écosystème
    </a>

    <div class="page-header">
        <h1>Modifier : {{ $partner->name }}</h1>
        <p>Mettez à jour les informations du partenaire</p>
    </div>

    <div class="card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.ecosystem.update', $partner) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="form-label">Nom de l'association</label>
                <input type="text" name="name" id="name" required class="form-input" value="{{ old('name', $partner->name) }}">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="form-label">Présentation <span class="text-gray-400 font-normal">optionnel</span></label>
                <textarea name="description" id="description" rows="3" class="form-input">{{ old('description', $partner->description) }}</textarea>
            </div>

            <div>
                <label for="logo" class="form-label">Logo</label>
                @if($partner->logo)
                    <div class="mb-3 flex items-center gap-4">
                        <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->name }}" class="h-16 w-16 object-contain rounded-lg border border-gray-200 bg-white p-1">
                        <span class="text-sm text-gray-500">Logo actuel</span>
                    </div>
                @endif
                <input type="file" name="logo" id="logo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-accordeur-50 file:text-accordeur-600 hover:file:bg-accordeur-100 file:cursor-pointer file:transition-colors">
                @error('logo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="contact_name" class="form-label">Nom du contact <span class="text-gray-400 font-normal">optionnel</span></label>
                <input type="text" name="contact_name" id="contact_name" class="form-input" value="{{ old('contact_name', $partner->contact_name) }}">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="contact_email" class="form-label">Email <span class="text-gray-400 font-normal">optionnel</span></label>
                    <input type="email" name="contact_email" id="contact_email" class="form-input" value="{{ old('contact_email', $partner->contact_email) }}">
                    @error('contact_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="contact_phone" class="form-label">Téléphone <span class="text-gray-400 font-normal">optionnel</span></label>
                    <input type="text" name="contact_phone" id="contact_phone" class="form-input" value="{{ old('contact_phone', $partner->contact_phone) }}">
                </div>
            </div>

            <div>
                <label for="website" class="form-label">Site web <span class="text-gray-400 font-normal">optionnel</span></label>
                <input type="url" name="website" id="website" class="form-input" value="{{ old('website', $partner->website) }}">
                @error('website') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="sort_order" class="form-label">Ordre d'affichage <span class="text-gray-400 font-normal">optionnel</span></label>
                    <input type="number" name="sort_order" id="sort_order" class="form-input" value="{{ old('sort_order', $partner->sort_order) }}">
                </div>
                <div class="flex items-end pb-2">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="active" id="active" value="1" {{ old('active', $partner->active) ? 'checked' : '' }} class="rounded border-gray-300 text-accordeur-500 focus:ring-accordeur-500">
                        <label for="active" class="text-sm font-medium text-gray-700">Visible sur le site</label>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="{{ route('admin.ecosystem.index') }}" class="btn-ghost">Annuler</a>
            </div>
        </form>
    </div>

</div>
@endsection

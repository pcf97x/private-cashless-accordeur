@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">

    <a href="{{ route('admin.time-slots.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-accordeur-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour aux créneaux
    </a>

    <div class="page-header">
        <h1>Modifier &mdash; {{ $timeSlot->label }}</h1>
        <p>Modifiez les paramètres de ce créneau horaire</p>
    </div>

    <div class="card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.time-slots.update', $timeSlot) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="code" class="form-label">Code</label>
                    <input type="text" name="code" id="code" required class="form-input" placeholder="Ex: MATIN" value="{{ old('code', $timeSlot->code) }}">
                    @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="label" class="form-label">Libellé</label>
                    <input type="text" name="label" id="label" required class="form-input" placeholder="Ex: Matinée" value="{{ old('label', $timeSlot->label) }}">
                    @error('label') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="start_time" class="form-label">Heure de début</label>
                    <input type="time" name="start_time" id="start_time" required class="form-input" value="{{ old('start_time', $timeSlot->start_time) }}">
                    @error('start_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="end_time" class="form-label">Heure de fin</label>
                    <input type="time" name="end_time" id="end_time" required class="form-input" value="{{ old('end_time', $timeSlot->end_time) }}">
                    @error('end_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="order_index" class="form-label">Ordre d'affichage</label>
                <input type="number" name="order_index" id="order_index" min="0" class="form-input" placeholder="0" value="{{ old('order_index', $timeSlot->order_index) }}">
                @error('order_index') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="active" id="active" value="1" {{ old('active', $timeSlot->active) ? 'checked' : '' }} class="rounded border-gray-300 text-accordeur-500 focus:ring-accordeur-500">
                <label for="active" class="text-sm font-medium text-gray-700">Créneau actif (disponible à la réservation)</label>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="{{ route('admin.time-slots.index') }}" class="btn-ghost">Annuler</a>
            </div>
        </form>
    </div>

</div>
@endsection

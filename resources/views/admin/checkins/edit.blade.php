@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">

    <a href="{{ route('checkins.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-accordeur-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour aux pointages
    </a>

    <div class="page-header">
        <h1>Modifier le visiteur</h1>
        <p class="text-sm text-gray-500 mt-1">Code : {{ $checkin->weez_ticket_code ?? $checkin->qr_token }}</p>
    </div>

    <div class="card p-6 sm:p-8">
        <form method="POST" action="{{ route('checkins.update', $checkin->weez_ticket_code) }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="firstname" class="form-label">Prenom</label>
                    <input type="text" name="firstname" id="firstname" class="form-input" value="{{ old('firstname', $checkin->firstname) }}">
                    @error('firstname') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="lastname" class="form-label">Nom</label>
                    <input type="text" name="lastname" id="lastname" class="form-input" value="{{ old('lastname', $checkin->lastname) }}">
                    @error('lastname') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="company" class="form-label">Societe</label>
                <input type="text" name="company" id="company" class="form-input" value="{{ old('company', $checkin->company) }}">
                @error('company') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-input" value="{{ old('email', $checkin->email) }}">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="purpose" class="form-label">Motif de visite</label>
                <select name="purpose" id="purpose" class="form-input">
                    <option value="">-- Choisir --</option>
                    <option value="resident" {{ old('purpose', $checkin->purpose) === 'resident' ? 'selected' : '' }}>Resident</option>
                    <option value="visiteur" {{ old('purpose', $checkin->purpose) === 'visiteur' ? 'selected' : '' }}>Visiteur</option>
                    <option value="autre" {{ old('purpose', $checkin->purpose) === 'autre' ? 'selected' : '' }}>Autre</option>
                </select>
                @error('purpose') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="{{ route('checkins.index') }}" class="btn-ghost">Annuler</a>
            </div>
        </form>
    </div>

</div>
@endsection

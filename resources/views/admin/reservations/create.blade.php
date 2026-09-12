@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">

    <a href="{{ route('admin.reservations.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-accordeur-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour aux reservations
    </a>

    <div class="page-header">
        <h1>Nouvelle reservation</h1>
        <p>Creer une reservation manuelle (sans paiement en ligne)</p>
    </div>

    <div class="card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.reservations.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="room_id" class="form-label">Salle</label>
                    <select name="room_id" id="room_id" required class="form-input">
                        <option value="">Choisir une salle...</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                        @endforeach
                    </select>
                    @error('room_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="date" class="form-label">Date</label>
                    <input type="date" name="date" id="date" required class="form-input" value="{{ old('date', now()->toDateString()) }}">
                    @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="time_slot_id" class="form-label">Creneau</label>
                    <select name="time_slot_id" id="time_slot_id" required class="form-input">
                        <option value="">Choisir un creneau...</option>
                        @foreach($timeSlots as $slot)
                            <option value="{{ $slot->id }}" {{ old('time_slot_id') == $slot->id ? 'selected' : '' }}>
                                {{ $slot->label }} ({{ \Carbon\Carbon::parse($slot->start_time)->format('H\hi') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H\hi') }})
                            </option>
                        @endforeach
                    </select>
                    @error('time_slot_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="pricing_profile_id" class="form-label">Profil tarifaire</label>
                    <select name="pricing_profile_id" id="pricing_profile_id" required class="form-input">
                        <option value="">Choisir...</option>
                        @foreach($profiles as $p)
                            <option value="{{ $p->id }}" {{ old('pricing_profile_id') == $p->id ? 'selected' : '' }}>{{ $p->label }}</option>
                        @endforeach
                    </select>
                    @error('pricing_profile_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <hr class="border-gray-100">

            <div>
                <label for="name" class="form-label">Nom complet du client</label>
                <input type="text" name="name" id="name" required class="form-input" placeholder="Jean Dupont" value="{{ old('name') }}">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" required class="form-input" placeholder="jean@exemple.com" value="{{ old('email') }}">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="phone" class="form-label">Telephone</label>
                    <input type="text" name="phone" id="phone" required class="form-input" placeholder="0694 00 00 00" value="{{ old('phone') }}">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="status" class="form-label">Statut</label>
                <select name="status" id="status" required class="form-input">
                    <option value="paid" {{ old('status') === 'paid' ? 'selected' : '' }}>Paye (reglement recu)</option>
                    <option value="gratuit" {{ old('status') === 'gratuit' ? 'selected' : '' }}>Gratuit (mise a disposition)</option>
                    <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>En attente de paiement</option>
                </select>
                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn-primary">Creer la reservation</button>
                <a href="{{ route('admin.reservations.index') }}" class="btn-ghost">Annuler</a>
            </div>
        </form>
    </div>

</div>
@endsection

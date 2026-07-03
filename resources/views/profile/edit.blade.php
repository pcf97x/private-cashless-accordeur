@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">

    <div class="page-header">
        <h1>Mon profil</h1>
        <p>Gérez vos informations personnelles et votre mot de passe</p>
    </div>

    {{-- Profile Information --}}
    <div class="card p-6 sm:p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-11 h-11 rounded-xl bg-accordeur-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-accordeur-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div>
                <h2 class="font-display font-bold text-gray-900">Informations personnelles</h2>
                <p class="text-xs text-gray-500">Mettez à jour votre nom et votre adresse email</p>
            </div>
        </div>

        <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf
            @method('patch')

            <div>
                <label for="name" class="form-label">Nom complet</label>
                <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $user->name) }}" required autofocus>
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="form-label">Adresse email</label>
                <input type="email" name="email" id="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                <button type="submit" class="btn-primary">Enregistrer</button>
                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-emerald-600 font-medium flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Enregistré
                    </p>
                @endif
            </div>
        </form>
    </div>

    {{-- Update Password --}}
    <div class="card p-6 sm:p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <div>
                <h2 class="font-display font-bold text-gray-900">Mot de passe</h2>
                <p class="text-xs text-gray-500">Utilisez un mot de passe long et unique pour sécuriser votre compte</p>
            </div>
        </div>

        <form method="post" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            @method('put')

            <div>
                <label for="current_password" class="form-label">Mot de passe actuel</label>
                <input type="password" name="current_password" id="current_password" class="form-input" autocomplete="current-password">
                @error('current_password', 'updatePassword') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="password" id="password" class="form-input" autocomplete="new-password">
                    @error('password', 'updatePassword') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="form-label">Confirmer</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" autocomplete="new-password">
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                <button type="submit" class="btn-primary">Changer le mot de passe</button>
                @if (session('status') === 'password-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-emerald-600 font-medium flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Mot de passe mis à jour
                    </p>
                @endif
            </div>
        </form>
    </div>

    {{-- Role info --}}
    <div class="card p-6 sm:p-8">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-11 h-11 rounded-xl bg-accordeur-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-accordeur-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div>
                <h2 class="font-display font-bold text-gray-900">Rôle & permissions</h2>
                <p class="text-xs text-gray-500">Votre niveau d'accès dans l'application</p>
            </div>
        </div>
        <div class="bg-gray-50 rounded-xl p-4 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Votre rôle actuel</p>
                <p class="font-display font-bold text-gray-900 mt-0.5">
                    @if($user->role === 'admin') Administrateur
                    @elseif($user->role === 'manager') Manager
                    @else Accueil
                    @endif
                </p>
            </div>
            @if($user->role === 'admin')
                <span class="badge badge-danger">Admin</span>
            @elseif($user->role === 'manager')
                <span class="badge badge-info">Manager</span>
            @else
                <span class="badge badge-success">Accueil</span>
            @endif
        </div>
    </div>

</div>
@endsection

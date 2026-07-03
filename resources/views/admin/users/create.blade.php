@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="page-header">
        <h1>Nouvel utilisateur</h1>
        <p>Créer un nouveau compte d'accès</p>
    </div>

    <div class="card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="form-label">Nom complet</label>
                <input type="text" name="name" id="name" class="form-input" value="{{ old('name') }}" required>
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}" required>
                @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="role" class="form-label">Rôle</label>
                <select name="role" id="role" class="form-input">
                    <option value="accueil" @selected(old('role') === 'accueil')>Accueil — Checkins et scan QR uniquement</option>
                    <option value="manager" @selected(old('role') === 'manager')>Manager — Réservations, contacts, checkins</option>
                    <option value="admin" @selected(old('role') === 'admin')>Admin — Accès complet</option>
                </select>
                @error('role') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-input" required>
                    @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="form-label">Confirmer</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" required>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Créer l'utilisateur</button>
                <a href="{{ route('admin.users.index') }}" class="btn-ghost">Annuler</a>
            </div>
        </form>
    </div>

</div>
@endsection

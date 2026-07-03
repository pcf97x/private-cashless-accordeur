@extends('layouts.public')

@section('title', 'Accès — L\'Accordeur')
@section('meta_description', 'Enregistrez votre visite à L\'Accordeur, Pôle Associatif de Guyane, en quelques secondes.')

@section('content')

    <main class="max-w-lg mx-auto px-6 py-10">

        {{-- Welcome --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-accordeur-50 mb-4">
                <svg class="w-8 h-8 text-accordeur-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Bienvenue</h1>
            <p class="text-gray-500 mt-1">Enregistrez votre visite en quelques secondes</p>
        </div>

        {{-- Form --}}
        <div class="card p-6 sm:p-8">
            <form method="POST" action="/acces" class="space-y-5">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="firstname" class="form-label">Prénom</label>
                        <input type="text" name="firstname" id="firstname" required class="form-input" placeholder="Jean">
                    </div>
                    <div>
                        <label for="lastname" class="form-label">Nom</label>
                        <input type="text" name="lastname" id="lastname" required class="form-input" placeholder="Dupont">
                    </div>
                </div>

                <div>
                    <label for="company" class="form-label">Société <span class="text-gray-400 font-normal">(optionnel)</span></label>
                    <input type="text" name="company" id="company" class="form-input" placeholder="Nom de votre société">
                </div>

                <div>
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-input" placeholder="jean.dupont@exemple.com">
                </div>

                <div>
                    <label for="phone" class="form-label">Téléphone</label>
                    <input type="text" name="phone" id="phone" class="form-input" placeholder="0694 00 00 00">
                </div>

                <div>
                    <label for="purpose" class="form-label">Motif de visite</label>
                    <select name="purpose" id="purpose" class="form-input">
                        <option value="resident">Résident</option>
                        <option value="visiteur">Visiteur</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>

                <button type="submit" class="btn-primary w-full justify-center py-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Valider mon accès
                </button>
            </form>
        </div>

    </main>

@endsection

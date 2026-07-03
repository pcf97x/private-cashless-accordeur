@extends('layouts.public')

@section('title', 'Pass Visiteur — L\'Accordeur')
@section('meta_description', 'Obtenez votre pass visiteur pour accéder à L\'Accordeur, Pôle Associatif de Guyane à Cayenne.')

@section('content')

<section class="relative overflow-hidden bg-gradient-to-b from-accordeur-50/80 via-white to-white min-h-[calc(100vh-80px)]">
    {{-- Decorative --}}
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute -top-32 -right-32 w-[500px] h-[500px] rounded-full bg-accordeur-100/30 blur-3xl"></div>
        <div class="absolute bottom-0 -left-32 w-[400px] h-[400px] rounded-full bg-accordeur-200/15 blur-3xl"></div>
    </div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-start">

            {{-- LEFT: Info --}}
            <div class="lg:sticky lg:top-28">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 backdrop-blur-sm border border-accordeur-100 shadow-sm mb-6">
                    <svg class="w-4 h-4 text-accordeur-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    <span class="text-sm font-semibold text-accordeur-700">Gratuit & instantané</span>
                </div>

                <h1 class="text-4xl lg:text-5xl font-display font-extrabold text-gray-900 leading-tight">
                    Votre <span class="text-gradient">Pass Visiteur</span>
                </h1>
                <p class="text-lg text-gray-500 mt-4 leading-relaxed">
                    Remplissez le formulaire pour recevoir votre pass d'accès à L'Accordeur. Vous recevrez un <strong class="text-gray-700">QR code par email</strong> à présenter à l'entrée.
                </p>

                {{-- Steps --}}
                <div class="mt-10 space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-accordeur-500 text-white flex items-center justify-center font-bold text-sm shrink-0">1</div>
                        <div>
                            <h3 class="font-display font-bold text-gray-900">Remplissez vos infos</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Nom, email et motif de visite</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-accordeur-400 text-white flex items-center justify-center font-bold text-sm shrink-0">2</div>
                        <div>
                            <h3 class="font-display font-bold text-gray-900">Recevez votre QR code</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Un pass unique vous est envoyé par email</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-accordeur-300 text-white flex items-center justify-center font-bold text-sm shrink-0">3</div>
                        <div>
                            <h3 class="font-display font-bold text-gray-900">Présentez-le à l'entrée</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Scannez votre pass à la borne d'accueil</p>
                        </div>
                    </div>
                </div>

                {{-- Trust --}}
                <div class="mt-10 flex items-center gap-3 text-sm text-gray-400">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>Vos données sont sécurisées et ne seront pas partagées</span>
                </div>
            </div>

            {{-- RIGHT: Form --}}
            <div>
                <div class="card p-6 sm:p-8 shadow-card-hover">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-11 h-11 rounded-xl bg-accordeur-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-accordeur-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        </div>
                        <div>
                            <h2 class="font-display font-bold text-gray-900">Vos informations</h2>
                            <p class="text-xs text-gray-400">* champs obligatoires</p>
                        </div>
                    </div>

                    <form method="POST" action="/acces" class="space-y-5">
                        @csrf

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="firstname" class="form-label">Prénom *</label>
                                <input type="text" name="firstname" id="firstname" required class="form-input" placeholder="Jean" value="{{ old('firstname') }}">
                                @error('firstname') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="lastname" class="form-label">Nom *</label>
                                <input type="text" name="lastname" id="lastname" required class="form-input" placeholder="Dupont" value="{{ old('lastname') }}">
                                @error('lastname') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="email" class="form-label">Email *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <input type="email" name="email" id="email" class="form-input !pl-10" placeholder="jean.dupont@exemple.com" value="{{ old('email') }}">
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Votre QR code sera envoyé à cette adresse</p>
                            @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="phone" class="form-label">Téléphone</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                <input type="text" name="phone" id="phone" class="form-input !pl-10" placeholder="0694 00 00 00" value="{{ old('phone') }}">
                            </div>
                        </div>

                        <div>
                            <label for="company" class="form-label">Société / Organisation</label>
                            <input type="text" name="company" id="company" class="form-input" placeholder="Nom de votre structure" value="{{ old('company') }}">
                        </div>

                        <div>
                            <label for="purpose" class="form-label">Motif de visite *</label>
                            <div class="grid grid-cols-3 gap-3" x-data="{ selected: '{{ old('purpose', 'visiteur') }}' }">
                                <label @click="selected = 'visiteur'" :class="selected === 'visiteur' ? 'border-accordeur-500 bg-accordeur-50 ring-2 ring-accordeur-500/20' : 'border-gray-200 hover:border-accordeur-300'" class="cursor-pointer flex flex-col items-center gap-2 p-4 rounded-xl border-2 transition-all duration-200">
                                    <svg class="w-6 h-6" :class="selected === 'visiteur' ? 'text-accordeur-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span class="text-xs font-semibold" :class="selected === 'visiteur' ? 'text-accordeur-700' : 'text-gray-600'">Visiteur</span>
                                    <input type="radio" name="purpose" value="visiteur" x-model="selected" class="sr-only">
                                </label>
                                <label @click="selected = 'resident'" :class="selected === 'resident' ? 'border-accordeur-500 bg-accordeur-50 ring-2 ring-accordeur-500/20' : 'border-gray-200 hover:border-accordeur-300'" class="cursor-pointer flex flex-col items-center gap-2 p-4 rounded-xl border-2 transition-all duration-200">
                                    <svg class="w-6 h-6" :class="selected === 'resident' ? 'text-accordeur-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <span class="text-xs font-semibold" :class="selected === 'resident' ? 'text-accordeur-700' : 'text-gray-600'">Résident</span>
                                    <input type="radio" name="purpose" value="resident" x-model="selected" class="sr-only">
                                </label>
                                <label @click="selected = 'autre'" :class="selected === 'autre' ? 'border-accordeur-500 bg-accordeur-50 ring-2 ring-accordeur-500/20' : 'border-gray-200 hover:border-accordeur-300'" class="cursor-pointer flex flex-col items-center gap-2 p-4 rounded-xl border-2 transition-all duration-200">
                                    <svg class="w-6 h-6" :class="selected === 'autre' ? 'text-accordeur-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span class="text-xs font-semibold" :class="selected === 'autre' ? 'text-accordeur-700' : 'text-gray-600'">Autre</span>
                                    <input type="radio" name="purpose" value="autre" x-model="selected" class="sr-only">
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn-accent w-full justify-center py-3.5 text-base mt-2 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                            Obtenir mon pass
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

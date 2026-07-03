@extends('layouts.public')

@section('content')

{{-- ============================================================== --}}
{{-- HERO SECTION                                                    --}}
{{-- ============================================================== --}}
<section class="relative overflow-hidden bg-gradient-to-b from-accordeur-600 via-accordeur-700 to-accordeur-800">
    {{-- Decorative blobs --}}
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute -top-24 -right-24 w-[400px] h-[400px] rounded-full bg-accordeur-500/20 blur-3xl"></div>
        <div class="absolute bottom-0 -left-32 w-[300px] h-[300px] rounded-full bg-accordeur-400/15 blur-3xl"></div>
        <div class="absolute top-16 left-1/4 w-16 h-16 rounded-full border-2 border-white/10"></div>
        <div class="absolute bottom-8 right-1/4 w-10 h-10 rounded-full border-2 border-white/10"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
        <div class="text-center max-w-3xl mx-auto">
            <h1 class="text-4xl lg:text-5xl font-display font-extrabold text-white leading-tight tracking-tight">
                Contactez-nous
            </h1>
            <p class="mt-4 text-lg text-accordeur-100 leading-relaxed">
                Une question ? Un projet ? Nous sommes à votre écoute.
            </p>
        </div>
    </div>
</section>

{{-- ============================================================== --}}
{{-- CONTACT FORM + INFO CARDS                                       --}}
{{-- ============================================================== --}}
<section class="py-16 lg:py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-10 lg:gap-12">

            {{-- -------------------------------------------------- --}}
            {{-- LEFT COLUMN — Contact Form (3/5)                    --}}
            {{-- -------------------------------------------------- --}}
            <div class="lg:col-span-3 order-2 lg:order-1">
                <div class="card p-6 sm:p-8">
                    <h2 class="text-2xl font-display font-bold text-gray-900 mb-6">
                        Envoyez-nous un message
                    </h2>

                    {{-- Success message --}}
                    @if(session('success'))
                        <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    @endif

                    {{-- Validation errors --}}
                    @if($errors->any())
                        <div class="mb-6 rounded-lg bg-rouge-50 border border-rouge-200 p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-rouge-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-rouge-700 mb-1">Veuillez corriger les erreurs suivantes :</p>
                                    <ul class="list-disc list-inside text-sm text-rouge-600 space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-5">
                        @csrf

                        {{-- Nom --}}
                        <div>
                            <label for="nom" class="form-label">Nom <span class="text-rouge-500">*</span></label>
                            <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required
                                   class="form-input @error('nom') border-rouge-400 focus:ring-rouge-500 @enderror"
                                   placeholder="Votre nom complet">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="form-label">Email <span class="text-rouge-500">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   class="form-input @error('email') border-rouge-400 focus:ring-rouge-500 @enderror"
                                   placeholder="votre@email.com">
                        </div>

                        {{-- Téléphone --}}
                        <div>
                            <label for="telephone" class="form-label">Téléphone <span class="text-gray-400 font-normal">(optionnel)</span></label>
                            <input type="tel" id="telephone" name="telephone" value="{{ old('telephone') }}"
                                   class="form-input @error('telephone') border-rouge-400 focus:ring-rouge-500 @enderror"
                                   placeholder="0694 XX XX XX">
                        </div>

                        {{-- Sujet --}}
                        <div>
                            <label for="sujet" class="form-label">Sujet <span class="text-rouge-500">*</span></label>
                            <select id="sujet" name="sujet" required
                                    class="form-input @error('sujet') border-rouge-400 focus:ring-rouge-500 @enderror">
                                <option value="" disabled {{ old('sujet') ? '' : 'selected' }}>Sélectionnez un sujet</option>
                                <option value="reservation" {{ old('sujet') === 'reservation' ? 'selected' : '' }}>Réservation de salle</option>
                                <option value="bureau" {{ old('sujet') === 'bureau' ? 'selected' : '' }}>Demande de bureau</option>
                                <option value="partenariat" {{ old('sujet') === 'partenariat' ? 'selected' : '' }}>Partenariat</option>
                                <option value="evenement" {{ old('sujet') === 'evenement' ? 'selected' : '' }}>Événement</option>
                                <option value="autre" {{ old('sujet') === 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>

                        {{-- Message --}}
                        <div>
                            <label for="message" class="form-label">Message <span class="text-rouge-500">*</span></label>
                            <textarea id="message" name="message" rows="5" required
                                      class="form-input @error('message') border-rouge-400 focus:ring-rouge-500 @enderror"
                                      placeholder="Décrivez votre demande...">{{ old('message') }}</textarea>
                        </div>

                        {{-- Submit --}}
                        <div class="pt-2">
                            <button type="submit" class="btn-primary w-full sm:w-auto px-8 py-3 rounded-xl text-base font-semibold inline-flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Envoyer le message
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- -------------------------------------------------- --}}
            {{-- RIGHT COLUMN — Info Cards (2/5)                     --}}
            {{-- -------------------------------------------------- --}}
            <div class="lg:col-span-2 order-1 lg:order-2 space-y-5">

                {{-- Adresse --}}
                <div class="card p-5 flex items-start gap-4">
                    <div class="shrink-0 w-10 h-10 rounded-lg bg-accordeur-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-accordeur-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-1">Adresse</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            1, rue Roland BARRAT<br>
                            97300 Cayenne, Guyane française
                        </p>
                    </div>
                </div>

                {{-- Téléphone --}}
                <div class="card p-5 flex items-start gap-4">
                    <div class="shrink-0 w-10 h-10 rounded-lg bg-accordeur-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-accordeur-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-1">Téléphone</h3>
                        <a href="tel:+594594302136" class="text-sm text-accordeur-600 hover:text-accordeur-700 transition-colors">
                            0594 30 21 36
                        </a>
                    </div>
                </div>

                {{-- Email --}}
                <div class="card p-5 flex items-start gap-4">
                    <div class="shrink-0 w-10 h-10 rounded-lg bg-accordeur-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-accordeur-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-1">Email</h3>
                        <a href="mailto:contact@laccordeur.gf" class="text-sm text-accordeur-600 hover:text-accordeur-700 transition-colors">
                            contact@laccordeur.gf
                        </a>
                    </div>
                </div>

                {{-- Horaires --}}
                <div class="card p-5 flex items-start gap-4">
                    <div class="shrink-0 w-10 h-10 rounded-lg bg-accordeur-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-accordeur-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-1">Horaires</h3>
                        <div class="text-sm text-gray-600 space-y-0.5">
                            <p>Lundi - Vendredi : <span class="font-medium text-gray-700">8h00 - 17h00</span></p>
                            <p>Samedi - Dimanche : <span class="font-medium text-gray-500">Fermé</span></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- ============================================================== --}}
{{-- MAP SECTION                                                     --}}
{{-- ============================================================== --}}
<section class="pb-16 lg:pb-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card overflow-hidden">
            <div class="bg-gray-100 flex flex-col items-center justify-center py-20 px-6 text-center">
                <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l5.447 2.724A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
                <p class="text-gray-500 font-medium">Carte interactive bientôt disponible</p>
                <p class="text-sm text-gray-400 mt-1">1, rue Roland BARRAT — 97300 Cayenne</p>
            </div>
        </div>
    </div>
</section>

@endsection

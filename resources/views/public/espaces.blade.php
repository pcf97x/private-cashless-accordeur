@extends('layouts.public')

@section('title', 'Nos Espaces — L\'Accordeur')
@section('meta_description', 'Découvrez les espaces de L\'Accordeur : salles de réunion, coworking, bureaux associatifs, numlab et studio podcast. 1 600 m² à Cayenne, Guyane.')

@section('content')

{{-- ============================================================== --}}
{{-- HERO SECTION                                                    --}}
{{-- ============================================================== --}}
<section class="relative overflow-hidden bg-gradient-to-b from-accordeur-50/80 via-accordeur-50/30 to-white">
    {{-- Decorative blobs --}}
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute -top-24 -right-24 w-[500px] h-[500px] rounded-full bg-accordeur-100/40 blur-3xl"></div>
        <div class="absolute top-1/2 -left-32 w-[400px] h-[400px] rounded-full bg-accordeur-200/20 blur-3xl"></div>
        <div class="absolute bottom-0 right-1/3 w-[300px] h-[300px] rounded-full bg-rouge-100/20 blur-3xl"></div>
        <div class="absolute top-16 left-1/4 w-20 h-20 rounded-full border-2 border-accordeur-200/30"></div>
        <div class="absolute bottom-24 right-1/4 w-12 h-12 rounded-full border-2 border-rouge-200/30"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
        <div class="text-center max-w-4xl mx-auto">
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 backdrop-blur-sm border border-accordeur-100 shadow-sm mb-8"
                 x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                 style="transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);">
                <span class="w-2 h-2 rounded-full bg-rouge-500 animate-pulse"></span>
                <span class="text-sm font-semibold text-accordeur-700">L'Accordeur</span>
            </div>

            {{-- Headline --}}
            <h1 class="text-5xl lg:text-6xl font-display font-extrabold text-gray-900 leading-[1.1] tracking-tight"
                x-data="{ show: false }" x-intersect="show = true"
                :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                style="transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.1s;">
                Nos <span class="text-gradient">espaces</span>
            </h1>

            {{-- Subtitle --}}
            <p class="mt-6 text-lg lg:text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed"
               x-data="{ show: false }" x-intersect="show = true"
               :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
               style="transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.2s;">
                1&nbsp;600&nbsp;m&sup2; pens&eacute;s pour la collaboration, l'innovation et la solidarit&eacute;
            </p>

            {{-- Stats bar --}}
            <div class="mt-12 inline-flex items-center gap-4 lg:gap-0 lg:divide-x lg:divide-accordeur-200/50 bg-white/60 backdrop-blur-sm rounded-2xl border border-accordeur-100/50 shadow-sm px-6 py-4"
                 x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                 style="transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.3s;">
                <div class="lg:px-6 text-center" x-data="{ count: 0, target: 25 }" x-intersect.once="let i = setInterval(() => { count++; if(count >= target) clearInterval(i) }, 40)">
                    <div class="text-2xl font-display font-extrabold text-accordeur-600" x-text="count">0</div>
                    <div class="text-xs text-gray-500 font-medium mt-0.5">Bureaux</div>
                </div>
                <div class="lg:px-6 text-center" x-data="{ count: 0, target: 5 }" x-intersect.once="let i = setInterval(() => { count++; if(count >= target) clearInterval(i) }, 150)">
                    <div class="text-2xl font-display font-extrabold text-accordeur-600" x-text="count">0</div>
                    <div class="text-xs text-gray-500 font-medium mt-0.5">Salles</div>
                </div>
                <div class="lg:px-6 text-center">
                    <div class="text-2xl font-display font-extrabold text-accordeur-600">1 600</div>
                    <div class="text-xs text-gray-500 font-medium mt-0.5">m&sup2;</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Wave divider --}}
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 56" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
            <path d="M0 24C240 56 480 56 720 40C960 24 1200 0 1440 8V56H0V24Z" fill="white"/>
        </svg>
    </div>
</section>


{{-- ============================================================== --}}
{{-- SPACE TYPES NAVIGATION PILLS                                    --}}
{{-- ============================================================== --}}
<section class="py-8 bg-white sticky top-16 lg:top-20 z-40 border-b border-gray-100/80 backdrop-blur-lg bg-white/90"
         x-data="{ active: 'salles' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide -mx-4 px-4 snap-x">
            <a href="#salles" @click="active = 'salles'"
               :class="active === 'salles' ? 'bg-accordeur-600 text-white shadow-lg shadow-accordeur-500/25' : 'bg-gray-100 text-gray-600 hover:bg-accordeur-50 hover:text-accordeur-700'"
               class="flex-none snap-start px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-200 whitespace-nowrap">
                Salles de r&eacute;union
            </a>
            <a href="#evenementiel" @click="active = 'evenementiel'"
               :class="active === 'evenementiel' ? 'bg-accordeur-600 text-white shadow-lg shadow-accordeur-500/25' : 'bg-gray-100 text-gray-600 hover:bg-accordeur-50 hover:text-accordeur-700'"
               class="flex-none snap-start px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-200 whitespace-nowrap">
                Espace &eacute;v&eacute;nementiel
            </a>
            <a href="#coworking" @click="active = 'coworking'"
               :class="active === 'coworking' ? 'bg-accordeur-600 text-white shadow-lg shadow-accordeur-500/25' : 'bg-gray-100 text-gray-600 hover:bg-accordeur-50 hover:text-accordeur-700'"
               class="flex-none snap-start px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-200 whitespace-nowrap">
                Coworking
            </a>
            <a href="#bureaux" @click="active = 'bureaux'"
               :class="active === 'bureaux' ? 'bg-accordeur-600 text-white shadow-lg shadow-accordeur-500/25' : 'bg-gray-100 text-gray-600 hover:bg-accordeur-50 hover:text-accordeur-700'"
               class="flex-none snap-start px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-200 whitespace-nowrap">
                Bureaux
            </a>
            <a href="#numlab" @click="active = 'numlab'"
               :class="active === 'numlab' ? 'bg-accordeur-600 text-white shadow-lg shadow-accordeur-500/25' : 'bg-gray-100 text-gray-600 hover:bg-accordeur-50 hover:text-accordeur-700'"
               class="flex-none snap-start px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-200 whitespace-nowrap">
                Numlab
            </a>
            <a href="#studio" @click="active = 'studio'"
               :class="active === 'studio' ? 'bg-accordeur-600 text-white shadow-lg shadow-accordeur-500/25' : 'bg-gray-100 text-gray-600 hover:bg-accordeur-50 hover:text-accordeur-700'"
               class="flex-none snap-start px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-200 whitespace-nowrap">
                Studio
            </a>
        </div>
    </div>
</section>


{{-- ============================================================== --}}
{{-- MEETING ROOMS & EVENT SPACES (dynamic from $rooms)              --}}
{{-- ============================================================== --}}
<section id="salles" class="py-20 lg:py-28 bg-white scroll-mt-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section header --}}
        <div class="text-center max-w-3xl mx-auto mb-16"
             x-data="{ show: false }" x-intersect="show = true"
             :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
             style="transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);">
            <span class="badge-info text-xs uppercase tracking-widest font-bold mb-4 inline-block">R&eacute;servation en ligne</span>
            <h2 class="text-3xl lg:text-4xl font-display font-extrabold text-gray-900 mt-4">
                Salles de r&eacute;union <span class="text-gradient">& &eacute;v&eacute;nementiel</span>
            </h2>
            <p class="mt-4 text-lg text-gray-500">
                &Eacute;quip&eacute;es et pr&ecirc;tes pour vos r&eacute;unions, formations et &eacute;v&eacute;nements
            </p>
        </div>

        {{-- Room cards --}}
        <div class="space-y-12 lg:space-y-20" id="evenementiel">
            @foreach($rooms as $index => $room)
            <div class="card overflow-hidden"
                 x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
                 style="transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1) {{ $index * 0.05 }}s;">
                <div class="flex flex-col {{ $index % 2 === 0 ? 'lg:flex-row' : 'lg:flex-row-reverse' }}">

                    {{-- Image side --}}
                    <div class="lg:w-5/12 relative overflow-hidden">
                        @if($room->image)
                        <img src="{{ asset('storage/' . $room->image) }}"
                             alt="{{ $room->name }}"
                             class="w-full h-64 lg:h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                        <div class="w-full h-64 lg:h-full min-h-[320px] bg-gradient-to-br {{ $index % 3 === 0 ? 'from-accordeur-400 to-accordeur-600' : ($index % 3 === 1 ? 'from-rouge-400 to-rouge-600' : 'from-accordeur-500 to-rouge-500') }} flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-16 h-16 text-white/30 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <p class="text-white/50 text-sm font-medium mt-3">{{ $room->name }}</p>
                            </div>
                        </div>
                        @endif

                        {{-- Price badge overlay --}}
                        @if($room->price_range)
                        <div class="absolute top-4 {{ $index % 2 === 0 ? 'left-4' : 'right-4' }}">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/90 backdrop-blur-sm text-sm font-bold text-accordeur-700 shadow-lg">
                                <svg class="w-4 h-4 text-rouge-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                {{ $room->price_range }}
                            </span>
                        </div>
                        @endif
                    </div>

                    {{-- Content side --}}
                    <div class="lg:w-7/12 p-8 lg:p-10 xl:p-12 flex flex-col justify-center">
                        <h2 class="text-2xl lg:text-3xl font-display font-bold text-gray-900">
                            {{ $room->name }}
                        </h2>

                        @if($room->description)
                        <p class="mt-4 text-gray-500 leading-relaxed">
                            {{ $room->description }}
                        </p>
                        @endif

                        {{-- Info grid --}}
                        <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @if($room->capacity)
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-accordeur-50/50">
                                <div class="w-10 h-10 rounded-lg bg-accordeur-100 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-accordeur-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-400 font-medium">Capacit&eacute;</div>
                                    <div class="text-sm font-bold text-gray-900">{{ $room->capacity }} pers.</div>
                                </div>
                            </div>
                            @endif

                            @if($room->surface_m2)
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-rouge-50/50">
                                <div class="w-10 h-10 rounded-lg bg-rouge-100 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-rouge-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-400 font-medium">Surface</div>
                                    <div class="text-sm font-bold text-gray-900">{{ $room->surface_m2 }} m&sup2;</div>
                                </div>
                            </div>
                            @endif

                            @if($room->location)
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50">
                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-400 font-medium">Localisation</div>
                                    <div class="text-sm font-bold text-gray-900">{{ $room->location }}</div>
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- Equipment list --}}
                        @if($room->equipments)
                        @php
                            $equipmentList = is_array($room->equipments) ? $room->equipments : explode(',', $room->equipments);
                        @endphp
                        <div class="mt-6">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">&Eacute;quipements</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($equipmentList as $equipment)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-accordeur-50 text-sm text-accordeur-700 font-medium">
                                    <svg class="w-4 h-4 text-accordeur-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ trim($equipment) }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- CTA --}}
                        <div class="mt-8">
                            <a href="{{ route('reservation.show', $room) }}" class="btn-accent inline-flex items-center gap-2 px-8 py-3.5 text-base rounded-xl shadow-lg shadow-rouge-500/20 hover:shadow-xl hover:shadow-rouge-500/30 transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                R&eacute;server cette salle
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ============================================================== --}}
{{-- OTHER SPACES (static content)                                   --}}
{{-- ============================================================== --}}
<section class="py-20 lg:py-28 bg-gray-50/50" id="coworking">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section header --}}
        <div class="text-center max-w-3xl mx-auto mb-16"
             x-data="{ show: false }" x-intersect="show = true"
             :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
             style="transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);">
            <span class="badge-info text-xs uppercase tracking-widest font-bold mb-4 inline-block">Tous nos espaces</span>
            <h2 class="text-3xl lg:text-4xl font-display font-extrabold text-gray-900 mt-4">
                Un &eacute;cosyst&egrave;me <span class="text-gradient">complet</span>
            </h2>
            <p class="mt-4 text-lg text-gray-500">
                Bien plus que des salles de r&eacute;union, L'Accordeur offre un cadre adapt&eacute; &agrave; chaque besoin
            </p>
        </div>

        {{-- Cards grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">

            {{-- Coworking --}}
            <div class="card group p-0 overflow-hidden hover:-translate-y-1"
                 x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                 style="transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.05s;">
                <div class="flex flex-col h-full">
                    <div class="h-2 bg-gradient-to-r from-emerald-400 to-emerald-500"></div>
                    <div class="p-8 flex-1 flex flex-col">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center mb-6 group-hover:bg-emerald-100 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-display font-bold text-gray-900 mb-3">Espace Coworking</h3>
                        <p class="text-gray-500 leading-relaxed flex-1">
                            Postes de travail flexibles en open space, id&eacute;al pour les travailleurs ind&eacute;pendants et les associations. Un environnement stimulant pour travailler, &eacute;changer et d&eacute;velopper vos projets.
                        </p>
                        <div class="mt-6 flex flex-wrap gap-2">
                            <span class="badge-success text-xs">Wifi haut d&eacute;bit</span>
                            <span class="badge-success text-xs">Acc&egrave;s flexible</span>
                            <span class="badge-success text-xs">Caf&eacute; offert</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bureaux associatifs --}}
            <div id="bureaux" class="card group p-0 overflow-hidden hover:-translate-y-1 scroll-mt-32"
                 x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                 style="transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.1s;">
                <div class="flex flex-col h-full">
                    <div class="h-2 bg-gradient-to-r from-accordeur-400 to-accordeur-600"></div>
                    <div class="p-8 flex-1 flex flex-col">
                        <div class="w-14 h-14 rounded-2xl bg-accordeur-50 flex items-center justify-center mb-6 group-hover:bg-accordeur-100 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-7 h-7 text-accordeur-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-display font-bold text-gray-900 mb-3">Bureaux associatifs</h3>
                        <p class="text-gray-500 leading-relaxed flex-1">
                            25 bureaux meubl&eacute;s et &eacute;quip&eacute;s, attribu&eacute;s aux associations r&eacute;sidentes. Un espace d&eacute;di&eacute; pour ancrer durablement votre structure au c&oelig;ur de l'&eacute;cosyst&egrave;me guyanais.
                        </p>
                        <div class="mt-6 flex flex-wrap gap-2">
                            <span class="badge-info text-xs">25 bureaux</span>
                            <span class="badge-info text-xs">Meubl&eacute;s</span>
                            <span class="badge-info text-xs">&Eacute;quip&eacute;s</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Numlab --}}
            <div id="numlab" class="card group p-0 overflow-hidden hover:-translate-y-1 scroll-mt-32"
                 x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                 style="transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.15s;">
                <div class="flex flex-col h-full">
                    <div class="h-2 bg-gradient-to-r from-violet-400 to-violet-600"></div>
                    <div class="p-8 flex-1 flex flex-col">
                        <div class="w-14 h-14 rounded-2xl bg-violet-50 flex items-center justify-center mb-6 group-hover:bg-violet-100 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-7 h-7 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-display font-bold text-gray-900 mb-3">Numlab</h3>
                        <p class="text-gray-500 leading-relaxed flex-1">
                            Laboratoire num&eacute;rique pour accompagner la transition digitale des associations. Formations, ateliers et accompagnement personnalis&eacute; pour ma&icirc;triser les outils du num&eacute;rique.
                        </p>
                        <div class="mt-6 flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-violet-50 text-violet-700 text-xs font-semibold ring-1 ring-violet-500/10">Formations</span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-violet-50 text-violet-700 text-xs font-semibold ring-1 ring-violet-500/10">Accompagnement</span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-violet-50 text-violet-700 text-xs font-semibold ring-1 ring-violet-500/10">Transition digitale</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Studio Podcast --}}
            <div id="studio" class="card group p-0 overflow-hidden hover:-translate-y-1 scroll-mt-32"
                 x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                 style="transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.2s;">
                <div class="flex flex-col h-full">
                    <div class="h-2 bg-gradient-to-r from-pink-400 to-rouge-500"></div>
                    <div class="p-8 flex-1 flex flex-col">
                        <div class="w-14 h-14 rounded-2xl bg-pink-50 flex items-center justify-center mb-6 group-hover:bg-pink-100 group-hover:scale-110 transition-all duration-300">
                            <svg class="w-7 h-7 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-display font-bold text-gray-900 mb-3">Studio Podcast</h3>
                        <p class="text-gray-500 leading-relaxed flex-1">
                            Studio d'enregistrement professionnel pour vos podcasts et contenus audio. Un espace insonoris&eacute; et &eacute;quip&eacute; pour produire des contenus de qualit&eacute;.
                        </p>
                        <div class="mt-6 flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-pink-50 text-pink-700 text-xs font-semibold ring-1 ring-pink-500/10">Insonoris&eacute;</span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-pink-50 text-pink-700 text-xs font-semibold ring-1 ring-pink-500/10">&Eacute;quipement pro</span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-pink-50 text-pink-700 text-xs font-semibold ring-1 ring-pink-500/10">Production audio</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================== --}}
{{-- FINAL CTA SECTION                                               --}}
{{-- ============================================================== --}}
<section class="relative py-20 lg:py-28 bg-gradient-to-br from-accordeur-600 via-accordeur-700 to-accordeur-800 overflow-hidden">
    {{-- Decorative elements --}}
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute top-0 left-0 w-72 h-72 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/5 rounded-full translate-x-1/3 translate-y-1/3"></div>
        <div class="absolute top-1/2 left-1/2 w-40 h-40 bg-rouge-500/10 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute top-1/4 right-1/4 w-20 h-20 rounded-full border-2 border-white/10"></div>
        <div class="absolute bottom-1/3 left-1/3 w-12 h-12 rounded-full border-2 border-white/5"></div>
    </div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center"
         x-data="{ show: false }" x-intersect="show = true"
         :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
         style="transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1);">

        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 mb-8">
            <svg class="w-5 h-5 text-rouge-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="text-sm font-semibold text-accordeur-100">Cayenne, Guyane fran&ccedil;aise</span>
        </div>

        <h2 class="text-3xl lg:text-4xl xl:text-5xl font-display font-extrabold text-white leading-tight">
            Trouvez l'espace id&eacute;al<br class="hidden sm:block"> pour votre projet
        </h2>

        <p class="mt-5 text-lg text-accordeur-100 leading-relaxed max-w-2xl mx-auto">
            Que vous soyez une association, un ind&eacute;pendant ou un porteur de projet,
            L'Accordeur a l'espace qu'il vous faut.
        </p>

        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('reservation.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-accordeur-700 font-bold rounded-xl shadow-lg shadow-black/10 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                R&eacute;server une salle
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
            <a href="mailto:contact@laccordeur.gf" class="inline-flex items-center gap-2 px-8 py-4 bg-transparent border-2 border-white/30 text-white font-bold rounded-xl hover:bg-white/10 transition-all duration-200 text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Nous contacter
            </a>
        </div>
    </div>
</section>

@endsection

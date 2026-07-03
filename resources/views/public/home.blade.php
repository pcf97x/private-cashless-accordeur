@extends('layouts.public')

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
        <div class="absolute top-1/3 right-16 w-8 h-8 rounded-full bg-accordeur-300/20"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center max-w-4xl mx-auto">
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 backdrop-blur-sm border border-accordeur-100 shadow-sm mb-8"
                 x-data="{ show: false }" x-intersect="show = true"
                 x-transition:enter="transition ease-out duration-500"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'">
                <span class="w-2 h-2 rounded-full bg-rouge-500 animate-pulse"></span>
                <span class="text-sm font-semibold text-accordeur-700">Pôle Associatif de Guyane</span>
            </div>

            {{-- Headline --}}
            <h1 class="text-5xl lg:text-6xl font-display font-extrabold text-gray-900 leading-[1.1] tracking-tight"
                x-data="{ show: false }" x-intersect="show = true"
                :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                style="transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.1s;">
                L'espace qui <span class="text-gradient">accorde</span><br class="hidden sm:block"> vos projets
            </h1>

            {{-- Subtitle --}}
            <p class="mt-6 text-lg lg:text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed"
               x-data="{ show: false }" x-intersect="show = true"
               :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
               style="transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.2s;">
                1&nbsp;600&nbsp;m² dédiés à l'Économie Sociale et Solidaire au c&oelig;ur de Cayenne.
                Bureaux, salles de réunion, coworking et studio podcast.
            </p>

            {{-- CTAs --}}
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4"
                 x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                 style="transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.3s;">
                <a href="/reservation" class="btn-accent px-8 py-3.5 text-base rounded-xl shadow-lg shadow-rouge-500/20 hover:shadow-xl hover:shadow-rouge-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Réserver une salle
                </a>
                <a href="#espaces" class="btn-outline px-8 py-3.5 text-base rounded-xl">
                    Découvrir nos espaces
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </a>
            </div>

            {{-- Stats row --}}
            <div class="mt-16 flex flex-wrap items-center justify-center gap-6 lg:gap-0 lg:divide-x lg:divide-accordeur-200/50"
                 x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                 style="transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.4s;">
                <div class="lg:px-8 text-center" x-data="{ count: 0, target: 25 }" x-intersect.once="let i = setInterval(() => { count++; if(count >= target) clearInterval(i) }, 40)">
                    <div class="text-3xl font-display font-extrabold text-accordeur-600" x-text="count">0</div>
                    <div class="text-sm text-gray-500 font-medium mt-1">Bureaux</div>
                </div>
                <div class="lg:px-8 text-center" x-data="{ count: 0, target: 5 }" x-intersect.once="let i = setInterval(() => { count++; if(count >= target) clearInterval(i) }, 150)">
                    <div class="text-3xl font-display font-extrabold text-accordeur-600" x-text="count">0</div>
                    <div class="text-sm text-gray-500 font-medium mt-1">Salles</div>
                </div>
                <div class="lg:px-8 text-center" x-data="{ count: 0, target: 1 }" x-intersect.once="let i = setInterval(() => { count++; if(count >= target) clearInterval(i) }, 100)">
                    <div class="text-3xl font-display font-extrabold text-accordeur-600" x-text="count">0</div>
                    <div class="text-sm text-gray-500 font-medium mt-1">Numlab</div>
                </div>
                <div class="lg:px-8 text-center" x-data="{ count: 0, target: 1 }" x-intersect.once="let i = setInterval(() => { count++; if(count >= target) clearInterval(i) }, 100)">
                    <div class="text-3xl font-display font-extrabold text-accordeur-600" x-text="count">0</div>
                    <div class="text-sm text-gray-500 font-medium mt-1">Studio podcast</div>
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
{{-- SERVICES / ESPACES SECTION                                      --}}
{{-- ============================================================== --}}
<section id="espaces" class="py-20 lg:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section header --}}
        <div class="text-center max-w-3xl mx-auto mb-16"
             x-data="{ show: false }" x-intersect="show = true"
             :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
             style="transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);">
            <span class="badge-info text-xs uppercase tracking-widest font-bold mb-4 inline-block">Nos espaces</span>
            <h2 class="text-3xl lg:text-4xl font-display font-extrabold text-gray-900 mt-4">
                Tout ce qu'il vous faut,<br class="hidden sm:block"> <span class="text-gradient">en un seul lieu</span>
            </h2>
        </div>

        {{-- Cards grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">

            {{-- Card 1: Bureaux --}}
            <div class="card group p-8 hover:-translate-y-1"
                 x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                 style="transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.05s;">
                <div class="w-14 h-14 rounded-2xl bg-accordeur-50 flex items-center justify-center mb-6 group-hover:bg-accordeur-100 group-hover:scale-110 transition-all duration-300">
                    <svg class="w-7 h-7 text-accordeur-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="text-xl font-display font-bold text-gray-900 mb-2">Bureaux associatifs</h3>
                <p class="text-gray-500 leading-relaxed">25 bureaux meublés et équipés, prêts à accueillir votre association ou structure ESS.</p>
            </div>

            {{-- Card 2: Salles de réunion --}}
            <div class="card group p-8 hover:-translate-y-1"
                 x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                 style="transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.1s;">
                <div class="w-14 h-14 rounded-2xl bg-rouge-50 flex items-center justify-center mb-6 group-hover:bg-rouge-100 group-hover:scale-110 transition-all duration-300">
                    <svg class="w-7 h-7 text-rouge-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h3 class="text-xl font-display font-bold text-gray-900 mb-2">Salles de réunion</h3>
                <p class="text-gray-500 leading-relaxed">4 salles de 14 à 24 personnes, parfaitement équipées pour vos réunions et formations.</p>
            </div>

            {{-- Card 3: Événementiel --}}
            <div class="card group p-8 hover:-translate-y-1"
                 x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                 style="transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.15s;">
                <div class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center mb-6 group-hover:bg-amber-100 group-hover:scale-110 transition-all duration-300">
                    <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
                <h3 class="text-xl font-display font-bold text-gray-900 mb-2">Espace événementiel</h3>
                <p class="text-gray-500 leading-relaxed">Salle de 70 personnes pour vos événements, séminaires et conférences.</p>
            </div>

            {{-- Card 4: Coworking --}}
            <div class="card group p-8 hover:-translate-y-1"
                 x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                 style="transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.2s;">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center mb-6 group-hover:bg-emerald-100 group-hover:scale-110 transition-all duration-300">
                    <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-xl font-display font-bold text-gray-900 mb-2">Espace coworking</h3>
                <p class="text-gray-500 leading-relaxed">Postes de travail flexibles en open space pour les travailleurs indépendants et porteurs de projets.</p>
            </div>

            {{-- Card 5: Numlab --}}
            <div class="card group p-8 hover:-translate-y-1"
                 x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                 style="transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.25s;">
                <div class="w-14 h-14 rounded-2xl bg-violet-50 flex items-center justify-center mb-6 group-hover:bg-violet-100 group-hover:scale-110 transition-all duration-300">
                    <svg class="w-7 h-7 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                </div>
                <h3 class="text-xl font-display font-bold text-gray-900 mb-2">Numlab</h3>
                <p class="text-gray-500 leading-relaxed">Laboratoire numérique pour vos projets digitaux, prototypage et formations tech.</p>
            </div>

            {{-- Card 6: Studio podcast --}}
            <div class="card group p-8 hover:-translate-y-1"
                 x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                 style="transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.3s;">
                <div class="w-14 h-14 rounded-2xl bg-pink-50 flex items-center justify-center mb-6 group-hover:bg-pink-100 group-hover:scale-110 transition-all duration-300">
                    <svg class="w-7 h-7 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                </div>
                <h3 class="text-xl font-display font-bold text-gray-900 mb-2">Studio podcast</h3>
                <p class="text-gray-500 leading-relaxed">Enregistrez vos podcasts dans un studio professionnel insonorisé et équipé.</p>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================== --}}
{{-- ROOMS PREVIEW SECTION                                           --}}
{{-- ============================================================== --}}
<section class="py-20 lg:py-28 bg-gray-50/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section header --}}
        <div class="text-center max-w-3xl mx-auto mb-16"
             x-data="{ show: false }" x-intersect="show = true"
             :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
             style="transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);">
            <span class="badge-info text-xs uppercase tracking-widest font-bold mb-4 inline-block">Réservation en ligne</span>
            <h2 class="text-3xl lg:text-4xl font-display font-extrabold text-gray-900 mt-4">
                Nos salles de réunion
            </h2>
            <p class="mt-4 text-lg text-gray-500">
                Équipées et prêtes pour vos réunions, formations et événements
            </p>
        </div>

        {{-- Rooms grid: 3 + 2 on desktop, horizontal scroll on mobile --}}
        <div class="flex lg:hidden gap-4 overflow-x-auto pb-4 snap-x snap-mandatory -mx-4 px-4 scrollbar-hide">
            @foreach([
                ['name' => 'Salle Événementielle', 'capacity' => 70, 'price_min' => 250, 'price_max' => 800, 'equipment' => ['Sono', 'Micro', 'Vidéoprojecteur'], 'highlight' => true],
                ['name' => 'Salle 1', 'capacity' => 14, 'price_min' => 50, 'price_max' => 150, 'equipment' => ['Écran', 'Vidéoprojecteur', 'Visioconférence']],
                ['name' => 'Salle 2', 'capacity' => 15, 'price_min' => 40, 'price_max' => 125, 'equipment' => ['Paperboard', 'Wifi', 'Visioconférence']],
                ['name' => 'Salle 3', 'capacity' => 18, 'price_min' => 80, 'price_max' => 250, 'equipment' => ['Paperboard', 'Wifi', 'Visioconférence']],
                ['name' => 'Salle 4', 'capacity' => 24, 'price_min' => 100, 'price_max' => 300, 'equipment' => ['Paperboard', 'Wifi', 'Visioconférence']],
            ] as $room)
            <div class="flex-none w-[300px] snap-start">
                <div class="card p-6 h-full flex flex-col {{ !empty($room['highlight']) ? 'ring-2 ring-rouge-500/20 border-rouge-100' : '' }}">
                    @if(!empty($room['highlight']))
                    <span class="badge bg-rouge-50 text-rouge-600 ring-1 ring-rouge-500/10 text-[10px] uppercase tracking-wider font-bold self-start mb-4">Espace premium</span>
                    @endif
                    <h3 class="text-lg font-display font-bold text-gray-900">{{ $room['name'] }}</h3>
                    <div class="flex items-center gap-3 mt-3">
                        <span class="badge-info">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $room['capacity'] }} pers.
                        </span>
                        <span class="text-sm font-bold text-gray-900">{{ $room['price_min'] }}&euro; — {{ $room['price_max'] }}&euro;</span>
                    </div>
                    <ul class="mt-4 space-y-1.5 flex-1">
                        @foreach($room['equipment'] as $eq)
                        <li class="flex items-center gap-2 text-sm text-gray-500">
                            <svg class="w-4 h-4 text-accordeur-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $eq }}
                        </li>
                        @endforeach
                    </ul>
                    <a href="/reservation" class="btn-primary w-full mt-6 text-sm py-2.5">
                        Réserver
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Desktop grid: 3 + 2 centered --}}
        <div class="hidden lg:grid lg:grid-cols-3 gap-6 mb-6">
            @foreach([
                ['name' => 'Salle Événementielle', 'capacity' => 70, 'price_min' => 250, 'price_max' => 800, 'equipment' => ['Sono', 'Micro', 'Vidéoprojecteur'], 'highlight' => true],
                ['name' => 'Salle 1', 'capacity' => 14, 'price_min' => 50, 'price_max' => 150, 'equipment' => ['Écran', 'Vidéoprojecteur', 'Visioconférence']],
                ['name' => 'Salle 2', 'capacity' => 15, 'price_min' => 40, 'price_max' => 125, 'equipment' => ['Paperboard', 'Wifi', 'Visioconférence']],
            ] as $room)
            <div class="card p-6 flex flex-col hover:-translate-y-1 {{ !empty($room['highlight']) ? 'ring-2 ring-rouge-500/20 border-rouge-100' : '' }}"
                 x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                 style="transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1) {{ $loop->index * 0.08 }}s;">
                @if(!empty($room['highlight']))
                <span class="badge bg-rouge-50 text-rouge-600 ring-1 ring-rouge-500/10 text-[10px] uppercase tracking-wider font-bold self-start mb-4">Espace premium</span>
                @endif
                <h3 class="text-lg font-display font-bold text-gray-900">{{ $room['name'] }}</h3>
                <div class="flex items-center gap-3 mt-3">
                    <span class="badge-info">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $room['capacity'] }} pers.
                    </span>
                    <span class="text-sm font-bold text-gray-900">{{ $room['price_min'] }}&euro; — {{ $room['price_max'] }}&euro;</span>
                </div>
                <ul class="mt-4 space-y-1.5 flex-1">
                    @foreach($room['equipment'] as $eq)
                    <li class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4 text-accordeur-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ $eq }}
                    </li>
                    @endforeach
                </ul>
                <a href="/reservation" class="btn-primary w-full mt-6 text-sm py-2.5">
                    Réserver
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
            @endforeach
        </div>

        <div class="hidden lg:grid lg:grid-cols-3 gap-6">
            <div class="col-start-1 col-end-2 lg:col-start-1"></div>
            @foreach([
                ['name' => 'Salle 3', 'capacity' => 18, 'price_min' => 80, 'price_max' => 250, 'equipment' => ['Paperboard', 'Wifi', 'Visioconférence']],
                ['name' => 'Salle 4', 'capacity' => 24, 'price_min' => 100, 'price_max' => 300, 'equipment' => ['Paperboard', 'Wifi', 'Visioconférence']],
            ] as $index => $room)
            <div class="card p-6 flex flex-col hover:-translate-y-1 {{ $index === 0 ? 'lg:col-start-1 lg:col-end-2' : '' }}"
                 x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                 style="transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1) {{ $index * 0.08 }}s;">
                <h3 class="text-lg font-display font-bold text-gray-900">{{ $room['name'] }}</h3>
                <div class="flex items-center gap-3 mt-3">
                    <span class="badge-info">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $room['capacity'] }} pers.
                    </span>
                    <span class="text-sm font-bold text-gray-900">{{ $room['price_min'] }}&euro; — {{ $room['price_max'] }}&euro;</span>
                </div>
                <ul class="mt-4 space-y-1.5 flex-1">
                    @foreach($room['equipment'] as $eq)
                    <li class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4 text-accordeur-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ $eq }}
                    </li>
                    @endforeach
                </ul>
                <a href="/reservation" class="btn-primary w-full mt-6 text-sm py-2.5">
                    Réserver
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
            @endforeach
            <div></div>
        </div>
    </div>
</section>


{{-- ============================================================== --}}
{{-- PLANNING TEASER SECTION                                         --}}
{{-- ============================================================== --}}
<section class="relative py-20 lg:py-28 bg-gradient-to-br from-accordeur-500 via-accordeur-600 to-accordeur-700 overflow-hidden">
    {{-- Decorative elements --}}
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute top-0 left-0 w-72 h-72 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/5 rounded-full translate-x-1/3 translate-y-1/3"></div>
        <div class="absolute top-1/2 left-1/2 w-40 h-40 bg-rouge-500/10 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
            {{-- Text content --}}
            <div class="flex-1 text-center lg:text-left"
                 x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                 style="transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-sm text-accordeur-100 text-xs font-bold uppercase tracking-widest mb-6">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Nouveau
                </span>
                <h2 class="text-3xl lg:text-4xl font-display font-extrabold text-white leading-tight">
                    Consultez notre planning<br class="hidden lg:block"> en temps réel
                </h2>
                <p class="mt-5 text-lg text-accordeur-100 leading-relaxed max-w-xl">
                    Fini les PDF ! Visualisez les disponibilités de toutes nos salles en un clic.
                    Réservez directement en ligne, 24h/24.
                </p>
                <div class="mt-8">
                    <a href="/planning" class="inline-flex items-center gap-2 px-8 py-3.5 bg-white text-accordeur-700 font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 text-base">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Voir le planning
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>

            {{-- Calendar illustration --}}
            <div class="flex-shrink-0"
                 x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 translate-y-8 scale-95'"
                 style="transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.15s;">
                <div class="relative">
                    {{-- Calendar card --}}
                    <div class="w-72 lg:w-80 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 p-6 shadow-2xl">
                        <div class="flex items-center justify-between mb-5">
                            <div class="text-white font-display font-bold text-lg">Juillet 2026</div>
                            <div class="flex gap-1">
                                <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                </div>
                                <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-center text-xs text-accordeur-200 mb-2">
                            <span>Lu</span><span>Ma</span><span>Me</span><span>Je</span><span>Ve</span><span>Sa</span><span>Di</span>
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-center text-sm">
                            @for($i = 0; $i < 2; $i++)
                            <div class="w-8 h-8 rounded-lg"></div>
                            @endfor
                            @for($d = 1; $d <= 31; $d++)
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white/80 hover:bg-white/10 transition-colors cursor-pointer
                                {{ $d == 3 ? 'bg-rouge-500 text-white font-bold ring-2 ring-rouge-400/50' : '' }}
                                {{ in_array($d, [7, 8, 14, 15, 21, 22, 28, 29]) ? 'bg-white/5' : '' }}">
                                {{ $d }}
                            </div>
                            @endfor
                        </div>
                        {{-- Mini event list --}}
                        <div class="mt-4 space-y-2">
                            <div class="flex items-center gap-2 bg-white/10 rounded-lg px-3 py-2">
                                <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                                <span class="text-xs text-white/90 font-medium">Salle 1 — 09h-12h</span>
                                <span class="badge-success text-[10px] ml-auto">Libre</span>
                            </div>
                            <div class="flex items-center gap-2 bg-white/10 rounded-lg px-3 py-2">
                                <div class="w-2 h-2 rounded-full bg-rouge-400"></div>
                                <span class="text-xs text-white/90 font-medium">Salle 2 — 14h-17h</span>
                                <span class="badge bg-rouge-500/20 text-rouge-200 ring-1 ring-rouge-400/20 text-[10px]">Réservée</span>
                            </div>
                        </div>
                    </div>
                    {{-- Floating badge --}}
                    <div class="absolute -top-3 -right-3 bg-rouge-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg animate-bounce">
                        En direct
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================== --}}
{{-- ABOUT / VALUES SECTION                                          --}}
{{-- ============================================================== --}}
<section class="py-20 lg:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section header --}}
        <div class="text-center max-w-3xl mx-auto mb-16"
             x-data="{ show: false }" x-intersect="show = true"
             :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
             style="transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);">
            <span class="badge-info text-xs uppercase tracking-widest font-bold mb-4 inline-block">Notre mission</span>
            <h2 class="text-3xl lg:text-4xl font-display font-extrabold text-gray-900 mt-4">
                L'Accordeur, <span class="text-gradient">Hub de coopération</span> territoriale
            </h2>
            <p class="mt-5 text-lg text-gray-500 leading-relaxed max-w-2xl mx-auto">
                Un espace de 1&nbsp;600&nbsp;m² dédié à l'Économie Sociale et Solidaire,
                pensé pour favoriser la coopération entre les acteurs du territoire guyanais.
            </p>
        </div>

        {{-- Values cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">

            {{-- Value 1: Coopération --}}
            <div class="relative group"
                 x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                 style="transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.05s;">
                <div class="absolute inset-0 bg-gradient-to-br from-accordeur-500 to-accordeur-600 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative card p-8 text-center group-hover:bg-transparent group-hover:border-transparent group-hover:shadow-none transition-all duration-500">
                    <div class="w-16 h-16 rounded-2xl bg-accordeur-50 group-hover:bg-white/20 flex items-center justify-center mx-auto mb-6 transition-all duration-500">
                        <svg class="w-8 h-8 text-accordeur-600 group-hover:text-white transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-display font-bold text-gray-900 group-hover:text-white mb-3 transition-colors duration-500">Coopération</h3>
                    <p class="text-gray-500 group-hover:text-white/80 leading-relaxed transition-colors duration-500">
                        Un lieu de rencontre pour tous les acteurs de l'ESS en Guyane. Partagez, échangez, construisez ensemble.
                    </p>
                </div>
            </div>

            {{-- Value 2: Innovation --}}
            <div class="relative group"
                 x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                 style="transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.15s;">
                <div class="absolute inset-0 bg-gradient-to-br from-rouge-500 to-rouge-600 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative card p-8 text-center group-hover:bg-transparent group-hover:border-transparent group-hover:shadow-none transition-all duration-500">
                    <div class="w-16 h-16 rounded-2xl bg-rouge-50 group-hover:bg-white/20 flex items-center justify-center mx-auto mb-6 transition-all duration-500">
                        <svg class="w-8 h-8 text-rouge-600 group-hover:text-white transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    </div>
                    <h3 class="text-xl font-display font-bold text-gray-900 group-hover:text-white mb-3 transition-colors duration-500">Innovation</h3>
                    <p class="text-gray-500 group-hover:text-white/80 leading-relaxed transition-colors duration-500">
                        Un numlab et un studio podcast pour innover, créer et diffuser vos projets numériques.
                    </p>
                </div>
            </div>

            {{-- Value 3: Solidarité --}}
            <div class="relative group"
                 x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                 style="transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.25s;">
                <div class="absolute inset-0 bg-gradient-to-br from-accordeur-500 to-accordeur-600 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative card p-8 text-center group-hover:bg-transparent group-hover:border-transparent group-hover:shadow-none transition-all duration-500">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-50 group-hover:bg-white/20 flex items-center justify-center mx-auto mb-6 transition-all duration-500">
                        <svg class="w-8 h-8 text-emerald-600 group-hover:text-white transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-display font-bold text-gray-900 group-hover:text-white mb-3 transition-colors duration-500">Solidarité</h3>
                    <p class="text-gray-500 group-hover:text-white/80 leading-relaxed transition-colors duration-500">
                        Porté par APROSEP, le groupement des associations guyanaises, au service de l'intérêt général.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================== --}}
{{-- CONTACT / CTA SECTION                                           --}}
{{-- ============================================================== --}}
<section class="py-20 lg:py-28 bg-gray-50/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20">

            {{-- Left: Contact info --}}
            <div x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                 style="transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);">
                <span class="badge-info text-xs uppercase tracking-widest font-bold mb-4 inline-block">Contact</span>
                <h2 class="text-3xl lg:text-4xl font-display font-extrabold text-gray-900 mt-4">
                    Venez nous <span class="text-gradient">rencontrer</span>
                </h2>
                <p class="mt-4 text-gray-500 leading-relaxed">
                    Notre équipe est à votre disposition pour vous faire visiter les locaux et répondre à toutes vos questions.
                </p>

                <div class="mt-8 space-y-5">
                    {{-- Address --}}
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 rounded-xl bg-accordeur-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-accordeur-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Adresse</div>
                            <div class="text-gray-500 mt-0.5">1, rue Roland BARRAT<br>97300 Cayenne, Guyane française</div>
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 rounded-xl bg-accordeur-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-accordeur-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Téléphone</div>
                            <a href="tel:0594302136" class="text-accordeur-600 hover:text-accordeur-700 font-medium mt-0.5 block">0594 30 21 36</a>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 rounded-xl bg-accordeur-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-accordeur-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Email</div>
                            <a href="mailto:contact@laccordeur.gf" class="text-accordeur-600 hover:text-accordeur-700 font-medium mt-0.5 block">contact@laccordeur.gf</a>
                        </div>
                    </div>

                    {{-- Hours --}}
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 rounded-xl bg-accordeur-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-accordeur-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Horaires</div>
                            <div class="text-gray-500 mt-0.5">Lundi — Vendredi : 7h30 — 17h00</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Map placeholder + CTA --}}
            <div x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                 style="transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.1s;">
                {{-- Map placeholder --}}
                <div class="card overflow-hidden">
                    <div class="relative bg-gradient-to-br from-gray-100 to-gray-200 h-64 lg:h-72 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-16 h-16 rounded-full bg-accordeur-500/10 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-accordeur-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <p class="text-sm font-medium text-gray-500">Cayenne, Guyane française</p>
                            <p class="text-xs text-gray-400 mt-1">1, rue Roland BARRAT — 97300</p>
                        </div>
                        {{-- Decorative dots --}}
                        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle, #326F7C 1px, transparent 1px); background-size: 20px 20px;"></div>
                    </div>

                    {{-- CTA card --}}
                    <div class="p-6 lg:p-8 bg-white">
                        <h3 class="text-lg font-display font-bold text-gray-900">
                            Réservez votre salle maintenant
                        </h3>
                        <p class="text-gray-500 text-sm mt-2 leading-relaxed">
                            Choisissez votre créneau en ligne et recevez votre confirmation immédiatement.
                        </p>
                        <div class="mt-5 flex flex-col sm:flex-row gap-3">
                            <a href="/reservation" class="btn-accent flex-1 text-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Réserver
                            </a>
                            <a href="/planning" class="btn-outline flex-1 text-center">
                                Voir le planning
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================== --}}
{{-- FOOTER CTA STRIP                                                --}}
{{-- ============================================================== --}}
<section class="relative py-16 bg-gradient-to-r from-accordeur-600 to-accordeur-700 overflow-hidden">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute -top-12 left-1/4 w-64 h-64 bg-white/5 rounded-full"></div>
        <div class="absolute -bottom-8 right-1/3 w-48 h-48 bg-rouge-500/10 rounded-full"></div>
    </div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center"
         x-data="{ show: false }" x-intersect="show = true"
         :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
         style="transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);">
        <h2 class="text-2xl lg:text-3xl font-display font-extrabold text-white">
            Prêt à rejoindre L'Accordeur ?
        </h2>
        <p class="mt-3 text-accordeur-100 text-lg">
            Réservez votre espace dès maintenant ou contactez-nous pour une visite.
        </p>
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="/reservation" class="inline-flex items-center gap-2 px-8 py-3.5 bg-white text-accordeur-700 font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 text-base">
                Réserver une salle
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            <a href="tel:0594302136" class="inline-flex items-center gap-2 px-8 py-3.5 bg-transparent border-2 border-white/30 text-white font-bold rounded-xl hover:bg-white/10 transition-all duration-200 text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                Nous appeler
            </a>
        </div>
    </div>
</section>

@endsection

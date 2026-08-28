@extends('layouts.public')

@section('title', 'Écosystème — L\'Accordeur')
@section('meta_description', 'Découvrez les associations et partenaires hébergés dans les bureaux associatifs de L\'Accordeur à Cayenne, Guyane.')

@section('content')

{{-- HERO --}}
<section class="relative overflow-hidden bg-gradient-to-b from-accordeur-50/80 via-accordeur-50/30 to-white">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute -top-24 -right-24 w-[500px] h-[500px] rounded-full bg-accordeur-100/40 blur-3xl"></div>
        <div class="absolute top-1/2 -left-32 w-[400px] h-[400px] rounded-full bg-accordeur-200/20 blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
        <div class="text-center max-w-4xl mx-auto">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 backdrop-blur-sm border border-accordeur-100 shadow-sm mb-8"
                 x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                 style="transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);">
                <span class="w-2 h-2 rounded-full bg-rouge-500 animate-pulse"></span>
                <span class="text-sm font-semibold text-accordeur-700">Bureaux associatifs</span>
            </div>

            <h1 class="text-5xl lg:text-6xl font-display font-extrabold text-gray-900 leading-[1.1] tracking-tight"
                x-data="{ show: false }" x-intersect="show = true"
                :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                style="transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.1s;">
                Notre <span class="text-gradient">&eacute;cosyst&egrave;me</span>
            </h1>

            <p class="mt-6 text-lg lg:text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed"
               x-data="{ show: false }" x-intersect="show = true"
               :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
               style="transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.2s;">
                D&eacute;couvrez les associations et partenaires h&eacute;berg&eacute;s au sein de L'Accordeur
            </p>

            <div class="mt-12 inline-flex items-center gap-4 bg-white/60 backdrop-blur-sm rounded-2xl border border-accordeur-100/50 shadow-sm px-6 py-4"
                 x-data="{ show: false }" x-intersect="show = true"
                 :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
                 style="transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.3s;">
                <div class="text-center" x-data="{ count: 0, target: {{ $partners->count() }} }" x-intersect.once="let i = setInterval(() => { count++; if(count >= target) clearInterval(i) }, 60)">
                    <div class="text-2xl font-display font-extrabold text-accordeur-600" x-text="count">0</div>
                    <div class="text-xs text-gray-500 font-medium mt-0.5">Partenaires actifs</div>
                </div>
            </div>
        </div>
    </div>

    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 56" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
            <path d="M0 24C240 56 480 56 720 40C960 24 1200 0 1440 8V56H0V24Z" fill="white"/>
        </svg>
    </div>
</section>

{{-- PARTNERS GRID --}}
<section class="py-20 lg:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($partners->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($partners as $index => $partner)
                <div class="card group p-0 overflow-hidden hover:-translate-y-1"
                     x-data="{ show: false }" x-intersect="show = true"
                     :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                     style="transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1) {{ ($index % 3) * 0.05 }}s;">
                    <div class="flex flex-col h-full">
                        <div class="h-2 bg-gradient-to-r from-accordeur-400 to-accordeur-600"></div>

                        {{-- Logo --}}
                        <div class="h-48 bg-white flex items-center justify-center p-8 border-b border-gray-100">
                            @if($partner->logo)
                                <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->name }}" class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-20 h-20 rounded-2xl bg-accordeur-50 flex items-center justify-center group-hover:bg-accordeur-100 group-hover:scale-110 transition-all duration-300">
                                    <span class="text-3xl font-display font-extrabold text-accordeur-400">{{ strtoupper(substr($partner->name, 0, 2)) }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="p-6 flex-1 flex flex-col">
                            <h3 class="text-xl font-display font-bold text-gray-900 mb-3">{{ $partner->name }}</h3>

                            @if($partner->description)
                                <p class="text-gray-500 leading-relaxed flex-1 mb-4">{{ $partner->description }}</p>
                            @endif

                            <div class="space-y-2 text-sm text-gray-600">
                                @if($partner->contact_name)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-accordeur-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <span>{{ $partner->contact_name }}</span>
                                    </div>
                                @endif
                                @if($partner->contact_email)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-accordeur-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <a href="mailto:{{ $partner->contact_email }}" class="text-accordeur-600 hover:underline">{{ $partner->contact_email }}</a>
                                    </div>
                                @endif
                                @if($partner->contact_phone)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-accordeur-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        <span>{{ $partner->contact_phone }}</span>
                                    </div>
                                @endif
                            </div>

                            @if($partner->website)
                                <div class="mt-6 pt-4 border-t border-gray-100">
                                    <a href="{{ $partner->website }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-sm font-semibold text-accordeur-600 hover:text-accordeur-700 transition-colors">
                                        Visiter le site
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <h3 class="text-lg font-display font-bold text-gray-900 mb-1">Bient&ocirc;t disponible</h3>
                <p class="text-gray-500">Les partenaires de l'&eacute;cosyst&egrave;me seront pr&eacute;sent&eacute;s ici prochainement.</p>
            </div>
        @endif

    </div>
</section>

{{-- CTA --}}
<section class="relative py-20 lg:py-28 bg-gradient-to-br from-accordeur-600 via-accordeur-700 to-accordeur-800 overflow-hidden">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute top-0 left-0 w-72 h-72 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/5 rounded-full translate-x-1/3 translate-y-1/3"></div>
    </div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center"
         x-data="{ show: false }" x-intersect="show = true"
         :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
         style="transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1);">

        <h2 class="text-3xl lg:text-4xl font-display font-extrabold text-white leading-tight">
            Rejoignez l'&eacute;cosyst&egrave;me<br class="hidden sm:block"> de L'Accordeur
        </h2>

        <p class="mt-5 text-lg text-accordeur-100 leading-relaxed max-w-2xl mx-auto">
            Vous &ecirc;tes une association et souhaitez int&eacute;grer nos bureaux associatifs ? Contactez-nous pour en savoir plus.
        </p>

        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-accordeur-700 font-bold rounded-xl shadow-lg shadow-black/10 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Nous contacter
            </a>
            <a href="{{ route('espaces') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-transparent border-2 border-white/30 text-white font-bold rounded-xl hover:bg-white/10 transition-all duration-200 text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour aux espaces
            </a>
        </div>
    </div>
</section>

@endsection

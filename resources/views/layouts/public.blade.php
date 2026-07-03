<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="@yield('meta_description', 'L\'Accordeur — Pôle de coopération de 1 600m² à Cayenne, Guyane. Coworking, salles de réunion, espaces associatifs. Réservez en ligne.')">
        <meta name="robots" content="index, follow">

        <meta property="og:title" content="@yield('title', 'L\'Accordeur — Pôle Territorial de Coopération Économique')">
        <meta property="og:description" content="@yield('meta_description', 'Pôle de coopération de 1 600m² à Cayenne. Coworking, salles de réunion, espaces associatifs.')">
        <meta property="og:type" content="website">
        <meta property="og:locale" content="fr_FR">

        <title>@yield('title', 'L\'Accordeur — Pôle Territorial de Coopération Économique')</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&family=plus-jakarta-sans:500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-white text-gray-900">

        {{-- ============================================================ --}}
        {{-- NAVIGATION --}}
        {{-- ============================================================ --}}
        <header
            x-data="{
                mobileOpen: false,
                scrolled: false,
                init() {
                    this.scrolled = window.scrollY > 20;
                    window.addEventListener('scroll', () => {
                        this.scrolled = window.scrollY > 20;
                    });
                }
            }"
            :class="scrolled ? 'shadow-lg shadow-gray-900/5 bg-white/90' : 'bg-white/70'"
            class="fixed top-0 inset-x-0 z-50 backdrop-blur-xl border-b border-gray-200/50 transition-all duration-300"
        >
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 lg:h-20">

                    {{-- Logo --}}
                    <a href="/" class="shrink-0">
                        <img src="{{ asset('images/logo-couleur.png') }}" alt="L'Accordeur" class="h-10">
                    </a>

                    {{-- Desktop navigation --}}
                    <div class="hidden lg:flex items-center gap-1">
                        <a href="/" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-accordeur-600 rounded-lg hover:bg-accordeur-50 transition-colors duration-200">
                            Accueil
                        </a>
                        <a href="/espaces" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-accordeur-600 rounded-lg hover:bg-accordeur-50 transition-colors duration-200">
                            Nos Espaces
                        </a>
                        <a href="/planning" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-accordeur-600 rounded-lg hover:bg-accordeur-50 transition-colors duration-200">
                            Planning
                        </a>
                        <a href="/acces" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-accordeur-600 rounded-lg hover:bg-accordeur-50 transition-colors duration-200">
                            Pass Visiteur
                        </a>
                        <a href="/contact" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-accordeur-600 rounded-lg hover:bg-accordeur-50 transition-colors duration-200">
                            Contact
                        </a>
                    </div>

                    {{-- Desktop actions --}}
                    <div class="hidden lg:flex items-center gap-4">
                        <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors duration-200">
                            Connexion
                        </a>
                        <a href="/reservation" class="btn-accent rounded-full px-6 py-2.5 text-sm font-semibold shadow-lg shadow-rouge-500/25 hover:shadow-xl hover:shadow-rouge-500/30 transition-all duration-200">
                            Réserver
                        </a>
                    </div>

                    {{-- Mobile hamburger --}}
                    <button
                        @click="mobileOpen = !mobileOpen"
                        class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors"
                        :aria-expanded="mobileOpen"
                        aria-label="Menu principal"
                    >
                        <svg x-show="!mobileOpen" class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-show="mobileOpen" x-cloak class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Mobile menu panel --}}
                <div
                    x-show="mobileOpen"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    x-cloak
                    class="lg:hidden border-t border-gray-100 pb-4"
                >
                    <div class="space-y-1 pt-3">
                        <a href="/" class="block px-4 py-3 text-base font-medium text-gray-700 hover:text-accordeur-600 hover:bg-accordeur-50 rounded-xl transition-colors">
                            Accueil
                        </a>
                        <a href="/espaces" class="block px-4 py-3 text-base font-medium text-gray-700 hover:text-accordeur-600 hover:bg-accordeur-50 rounded-xl transition-colors">
                            Nos Espaces
                        </a>
                        <a href="/planning" class="block px-4 py-3 text-base font-medium text-gray-700 hover:text-accordeur-600 hover:bg-accordeur-50 rounded-xl transition-colors">
                            Planning
                        </a>
                        <a href="/acces" class="block px-4 py-3 text-base font-medium text-gray-700 hover:text-accordeur-600 hover:bg-accordeur-50 rounded-xl transition-colors">
                            Pass Visiteur
                        </a>
                        <a href="/contact" class="block px-4 py-3 text-base font-medium text-gray-700 hover:text-accordeur-600 hover:bg-accordeur-50 rounded-xl transition-colors">
                            Contact
                        </a>
                    </div>
                    <div class="mt-4 px-4 space-y-3">
                        <a href="/reservation" class="btn-accent rounded-full w-full text-center block py-3 text-sm font-semibold">
                            Réserver
                        </a>
                        <a href="{{ route('login') }}" class="block text-center text-sm text-gray-500 hover:text-gray-700 transition-colors">
                            Connexion
                        </a>
                    </div>
                </div>
            </nav>
        </header>

        {{-- Spacer to offset fixed navbar --}}
        <div class="h-16 lg:h-20"></div>

        {{-- ============================================================ --}}
        {{-- MAIN CONTENT --}}
        {{-- ============================================================ --}}
        <main>
            @yield('content')
        </main>

        {{-- ============================================================ --}}
        {{-- FOOTER --}}
        {{-- ============================================================ --}}
        <footer class="bg-gradient-to-b from-accordeur-900 to-accordeur-950 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                {{-- Main footer content --}}
                <div class="py-16 lg:py-20 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">

                    {{-- Col 1: Brand --}}
                    <div class="lg:col-span-1">
                        <a href="/" class="inline-block">
                            <img src="{{ asset('images/logo-blanc.png') }}" alt="L'Accordeur" class="h-10 mb-4">
                        </a>
                        <p class="text-accordeur-200 text-xs font-semibold uppercase tracking-wider mb-3">
                            Pôle Territorial de Coopération Économique
                        </p>
                        <p class="text-accordeur-300 text-sm leading-relaxed">
                            1 600m² dédiés au coworking, à la coopération et à l'innovation en Guyane. Un lieu unique pour entreprendre, collaborer et créer ensemble.
                        </p>
                    </div>

                    {{-- Col 2: Navigation --}}
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-white mb-5">Navigation</h3>
                        <ul class="space-y-3">
                            <li>
                                <a href="/" class="text-accordeur-300 hover:text-white text-sm transition-colors duration-200 flex items-center gap-2 group">
                                    <span class="w-1 h-1 rounded-full bg-accordeur-500 group-hover:bg-rouge-400 transition-colors"></span>
                                    Accueil
                                </a>
                            </li>
                            <li>
                                <a href="#espaces" class="text-accordeur-300 hover:text-white text-sm transition-colors duration-200 flex items-center gap-2 group">
                                    <span class="w-1 h-1 rounded-full bg-accordeur-500 group-hover:bg-rouge-400 transition-colors"></span>
                                    Nos Espaces
                                </a>
                            </li>
                            <li>
                                <a href="#planning" class="text-accordeur-300 hover:text-white text-sm transition-colors duration-200 flex items-center gap-2 group">
                                    <span class="w-1 h-1 rounded-full bg-accordeur-500 group-hover:bg-rouge-400 transition-colors"></span>
                                    Planning
                                </a>
                            </li>
                            <li>
                                <a href="/reservation" class="text-accordeur-300 hover:text-white text-sm transition-colors duration-200 flex items-center gap-2 group">
                                    <span class="w-1 h-1 rounded-full bg-accordeur-500 group-hover:bg-rouge-400 transition-colors"></span>
                                    Réserver
                                </a>
                            </li>
                            <li>
                                <a href="#contact" class="text-accordeur-300 hover:text-white text-sm transition-colors duration-200 flex items-center gap-2 group">
                                    <span class="w-1 h-1 rounded-full bg-accordeur-500 group-hover:bg-rouge-400 transition-colors"></span>
                                    Contact
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- Col 3: Contact --}}
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-white mb-5">Contact</h3>
                        <ul class="space-y-4">
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-accordeur-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="text-accordeur-300 text-sm leading-relaxed">
                                    1 rue Roland BARRAT<br>
                                    97300 Cayenne<br>
                                    Guyane française
                                </span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-accordeur-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <a href="tel:+594594302136" class="text-accordeur-300 hover:text-white text-sm transition-colors duration-200">
                                    0594 30 21 36
                                </a>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-accordeur-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <a href="mailto:contact@laccordeur.fr" class="text-accordeur-300 hover:text-white text-sm transition-colors duration-200">
                                    contact@laccordeur.fr
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- Col 4: Horaires --}}
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-white mb-5">Horaires</h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-accordeur-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-accordeur-300 text-sm">Lundi — Vendredi</span>
                            </div>
                            <div class="pl-8">
                                <span class="text-white font-semibold text-lg">8h — 17h</span>
                            </div>
                            <div class="pl-8 pt-2">
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-accordeur-800/50 border border-accordeur-700/30">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span class="text-accordeur-200 text-xs font-medium">Accueil sur place</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 pt-5 border-t border-accordeur-800/50">
                            <p class="text-accordeur-400 text-xs">
                                Samedi, Dimanche &amp; jours fériés<br>
                                <span class="text-accordeur-500">Fermé</span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Bottom bar --}}
                <div class="border-t border-accordeur-800/50 py-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-accordeur-400 text-xs text-center sm:text-left">
                        &copy; 2025 L'Accordeur. Géré par
                        <span class="text-accordeur-300 font-medium">APROSEP</span>.
                        Tous droits réservés.
                    </p>
                    <div class="flex items-center gap-6">
                        <a href="#mentions-legales" class="text-accordeur-400 hover:text-accordeur-200 text-xs transition-colors duration-200">
                            Mentions légales
                        </a>
                        <a href="#confidentialite" class="text-accordeur-400 hover:text-accordeur-200 text-xs transition-colors duration-200">
                            Politique de confidentialité
                        </a>
                        <a href="#cgv" class="text-accordeur-400 hover:text-accordeur-200 text-xs transition-colors duration-200">
                            CGV
                        </a>
                    </div>
                </div>
            </div>
        </footer>

        @stack('scripts')
    </body>
</html>

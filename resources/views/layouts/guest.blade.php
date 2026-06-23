<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>L'Accordeur — Pôle Associatif de Guyane</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&family=plus-jakarta-sans:500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex">
            {{-- Left panel - branding --}}
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-accordeur-600 via-accordeur-500 to-accordeur-700 relative overflow-hidden">
                {{-- Decorative elements --}}
                <div class="absolute inset-0">
                    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                    <div class="absolute bottom-0 left-0 w-80 h-80 bg-white/5 rounded-full translate-y-1/3 -translate-x-1/3"></div>
                    <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-rouge-500/10 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
                </div>

                <div class="relative z-10 flex flex-col justify-between p-12 w-full">
                    <div>
                        <img src="{{ asset('images/logo-blanc.png') }}" alt="L'Accordeur" class="h-16">
                    </div>

                    <div class="space-y-6">
                        <h1 class="text-4xl font-display font-bold text-white leading-tight">
                            Bienvenue sur votre<br>
                            espace de gestion
                        </h1>
                        <p class="text-accordeur-100 text-lg leading-relaxed max-w-md">
                            Gérez vos réservations, accès visiteurs et suivez l'activité de votre pôle associatif en toute simplicité.
                        </p>
                        <div class="flex items-center gap-6 pt-4">
                            <div class="flex items-center gap-2 text-accordeur-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-sm">Réservations</span>
                            </div>
                            <div class="flex items-center gap-2 text-accordeur-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-sm">Check-in QR</span>
                            </div>
                            <div class="flex items-center gap-2 text-accordeur-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-sm">Paiement Stripe</span>
                            </div>
                        </div>
                    </div>

                    <p class="text-accordeur-300 text-xs">
                        &copy; {{ date('Y') }} L'Accordeur — Pôle Associatif de Guyane
                    </p>
                </div>
            </div>

            {{-- Right panel - form --}}
            <div class="flex-1 flex flex-col justify-center items-center p-8 bg-gray-50/50">
                <div class="w-full max-w-md">
                    {{-- Mobile logo --}}
                    <div class="lg:hidden flex justify-center mb-8">
                        <img src="{{ asset('images/logo-couleur.png') }}" alt="L'Accordeur" class="h-14">
                    </div>

                    <div class="bg-white rounded-2xl shadow-card border border-gray-100 p-8">
                        {{ $slot }}
                    </div>

                    <p class="text-center text-xs text-gray-400 mt-6 lg:hidden">
                        &copy; {{ date('Y') }} L'Accordeur — Pôle Associatif de Guyane
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>

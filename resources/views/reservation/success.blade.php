<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Réservation confirmée — L'Accordeur</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&family=plus-jakarta-sans:500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50/50 min-h-screen">

    <header class="bg-white border-b border-gray-100">
        <div class="max-w-3xl mx-auto px-6 py-4 flex items-center justify-center">
            <img src="{{ asset('images/logo-couleur.png') }}" alt="L'Accordeur" class="h-9">
        </div>
    </header>

    <main class="max-w-lg mx-auto px-6 py-12">
        <div class="card p-8 text-center">

            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-emerald-50 mb-5">
                <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>

            <h1 class="text-2xl font-display font-bold text-gray-900 mb-1">Réservation confirmée</h1>
            <p class="text-gray-500 mb-6">Merci <strong class="text-gray-900">{{ $reservation->name }}</strong>, tout est en ordre.</p>

            {{-- Details --}}
            <div class="bg-gray-50 rounded-xl p-5 text-left space-y-3 mb-6">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Salle</span>
                    <span class="font-semibold text-gray-900">{{ $reservation->room->name }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Date</span>
                    <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Créneau</span>
                    <span class="font-semibold text-gray-900">{{ $reservation->start_at->format('H:i') }} &rarr; {{ $reservation->end_at->format('H:i') }}</span>
                </div>
                <div class="flex justify-between text-sm border-t border-gray-200 pt-3">
                    <span class="text-gray-500">Montant payé</span>
                    <span class="font-bold text-accordeur-600 text-lg">{{ number_format($reservation->price, 2, ',', ' ') }} &euro;</span>
                </div>
            </div>

            <p class="text-sm text-gray-500 mb-6 flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-accordeur-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Un email de confirmation vous a été envoyé
            </p>

            {{-- Google Calendar --}}
            @php
                $start = $reservation->start_at->format('Ymd\THis');
                $end   = $reservation->end_at->format('Ymd\THis');
                $calendarUrl = 'https://www.google.com/calendar/render?action=TEMPLATE'
                    . '&text=' . urlencode('Réservation - ' . $reservation->room->name)
                    . '&dates=' . $start . '/' . $end
                    . '&details=' . urlencode('Réservation L\'Accordeur')
                    . '&location=' . urlencode($reservation->room->name . ' - L\'Accordeur, Cayenne');
            @endphp

            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ $calendarUrl }}" target="_blank" class="btn-outline flex-1 justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Ajouter au calendrier
                </a>
                <a href="{{ route('reservation.index') }}" class="btn-ghost flex-1 justify-center">
                    Retour aux salles
                </a>
            </div>

        </div>
    </main>

    <footer class="text-center text-xs text-gray-400 py-8">
        L'Accordeur &mdash; Pôle Associatif de Guyane
    </footer>

</body>
</html>

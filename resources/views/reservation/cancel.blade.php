<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Paiement annulé — L'Accordeur</title>
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

            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-red-50 mb-5">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>

            <h1 class="text-2xl font-display font-bold text-gray-900 mb-2">Paiement annulé</h1>
            <p class="text-gray-500 mb-6">Votre réservation #{{ $reservation->id }} n'a pas été finalisée.</p>

            <a href="{{ route('reservation.show', ['room' => $reservation->room_id]) }}" class="btn-primary justify-center w-full">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Réessayer
            </a>

        </div>
    </main>

</body>
</html>

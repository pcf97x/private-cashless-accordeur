<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accès validé — L'Accordeur</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&family=plus-jakarta-sans:500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50/50 min-h-screen">

    <header class="bg-white border-b border-gray-100">
        <div class="max-w-2xl mx-auto px-6 py-5 flex items-center justify-center">
            <img src="{{ asset('images/logo-couleur.png') }}" alt="L'Accordeur" class="h-10">
        </div>
    </header>

    <main class="max-w-lg mx-auto px-6 py-10">
        <div class="card p-8 text-center">

            @if($barcode)
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-emerald-50 mb-5">
                    <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>

                <h1 class="text-2xl font-display font-bold text-gray-900 mb-2">Accès validé</h1>
                <p class="text-gray-500 mb-6">Présentez ce QR code à l'accueil</p>

                <div class="inline-block p-4 bg-white rounded-2xl shadow-card border border-gray-100">
                    <img
                        src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ $barcode }}"
                        alt="QR Code"
                        class="w-56 h-56 rounded-xl"
                    />
                </div>

                <p class="text-xs text-gray-400 mt-4 font-mono">{{ $barcode }}</p>
            @else
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-red-50 mb-5">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>

                <h1 class="text-2xl font-display font-bold text-gray-900 mb-2">QR code indisponible</h1>
                <p class="text-gray-500">Une erreur est survenue lors de la génération. Veuillez réessayer.</p>
            @endif

            <div class="mt-8">
                <a href="/acces" class="btn-outline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Nouvelle entrée
                </a>
            </div>

        </div>

        <p class="text-center text-xs text-gray-400 mt-8">
            L'Accordeur &mdash; Pôle Associatif de Guyane
        </p>
    </main>

</body>
</html>

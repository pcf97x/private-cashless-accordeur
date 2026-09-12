<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devis - L'Accordeur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-lg p-8 text-center">

        @if($action === 'accepted')
            <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Devis accepte !</h1>
            <p class="text-gray-500 mb-6">Votre reservation est maintenant confirmee. Vous recevrez un email de confirmation.</p>

            <div class="bg-gray-50 rounded-xl p-4 text-left space-y-2 mb-6">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Salle</span>
                    <span class="text-sm font-semibold">{{ $reservation->room->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Date</span>
                    <span class="text-sm font-semibold">{{ $reservation->date->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Creneau</span>
                    <span class="text-sm font-semibold">{{ $reservation->start_at->format('H:i') }} - {{ $reservation->end_at->format('H:i') }}</span>
                </div>
                <div class="flex justify-between border-t pt-2">
                    <span class="text-sm text-gray-500">Montant</span>
                    <span class="text-lg font-bold text-emerald-600">{{ number_format($reservation->price, 2, ',', ' ') }} &euro;</span>
                </div>
            </div>

        @elseif($action === 'declined')
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Devis refuse</h1>
            <p class="text-gray-500 mb-6">Le devis a ete refuse. Le creneau a ete libere. N'hesitez pas a nous recontacter si vous changez d'avis.</p>

        @elseif($action === 'expired')
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Devis expire</h1>
            <p class="text-gray-500 mb-6">Ce devis n'est plus valide. Contactez-nous pour en obtenir un nouveau.</p>
        @endif

        <a href="{{ url('/') }}" class="inline-block text-sm text-teal-600 hover:text-teal-800 font-medium">
            &larr; Retour au site
        </a>
    </div>
</body>
</html>

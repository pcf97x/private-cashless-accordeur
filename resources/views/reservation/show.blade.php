<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $room->name }} — L'Accordeur</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&family=plus-jakarta-sans:500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50/50 min-h-screen">

    <header class="bg-white border-b border-gray-100 sticky top-0 z-10 backdrop-blur-lg bg-white/80">
        <div class="max-w-3xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="/"><img src="{{ asset('images/logo-couleur.png') }}" alt="L'Accordeur" class="h-9"></a>
            <a href="{{ route('reservation.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-accordeur-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Toutes les salles
            </a>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-6 py-10">

        {{-- Room info --}}
        <div class="card p-6 mb-8">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-xl bg-accordeur-50 flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7 text-accordeur-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <h1 class="text-2xl font-display font-bold text-gray-900">{{ $room->name }}</h1>
                    <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                        <span>{{ $room->capacity }} personnes</span>
                        @if($room->surface_m2)
                            <span>{{ $room->surface_m2 }} m&sup2;</span>
                        @endif
                    </div>
                    @if($room->equipments)
                        <p class="text-sm text-gray-400 mt-1">{{ $room->equipments }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Errors --}}
        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-3 rounded-xl text-sm">
                <ul class="list-disc ml-4 space-y-1">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Booking form --}}
        <div class="card p-6 sm:p-8">
            <h2 class="text-lg font-display font-bold text-gray-900 mb-6">Réserver cette salle</h2>

            <form method="POST" action="{{ route('reservation.store') }}" id="reservationForm" class="space-y-6">
                @csrf
                <input type="hidden" name="room_id" value="{{ $room->id }}">

                {{-- Date & slot --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="date" class="form-label">Date</label>
                        <input type="date" name="date" id="date" class="form-input" value="{{ old('date') }}" min="{{ date('Y-m-d') }}">
                    </div>
                    <div>
                        <label for="time_slot_id" class="form-label">Créneau</label>
                        <select name="time_slot_id" id="time_slot_id" class="form-input">
                            <option value="">Choisir...</option>
                            @foreach($timeSlots as $s)
                                <option value="{{ $s->id }}" @selected(old('time_slot_id')==$s->id)>{{ $s->label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="pricing_profile_id" class="form-label">Profil tarifaire</label>
                        <select name="pricing_profile_id" id="pricing_profile_id" class="form-input">
                            <option value="">Choisir...</option>
                            @foreach($pricingProfiles as $p)
                                <option value="{{ $p->id }}">{{ $p->label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Price display --}}
                <div class="bg-accordeur-50/50 rounded-xl p-4 flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Prix du créneau</span>
                    <span class="text-2xl font-display font-bold text-accordeur-600">
                        <span id="price_display">&mdash;</span> &euro;
                    </span>
                </div>

                {{-- Contact info --}}
                <div class="border-t border-gray-100 pt-6">
                    <h3 class="text-sm font-bold text-gray-700 mb-4">Vos coordonnées</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="name" class="form-label">Nom complet</label>
                            <input type="text" name="name" id="name" class="form-input" placeholder="Jean Dupont" value="{{ old('name', $user?->name) }}">
                        </div>
                        <div>
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-input" placeholder="jean@exemple.com" value="{{ old('email', $user?->email) }}">
                        </div>
                        <div>
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="text" name="phone" id="phone" class="form-input" placeholder="0694 00 00 00" value="{{ old('phone') }}">
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" id="reserveBtn" class="btn-accent w-full justify-center py-3.5 text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Réserver & Payer
                </button>

                <p id="loading" class="text-center text-sm text-accordeur-600 font-medium hidden">
                    Redirection vers le paiement...
                </p>
            </form>
        </div>

    </main>

    <footer class="text-center text-xs text-gray-400 py-8">
        L'Accordeur &mdash; Pôle Associatif de Guyane
    </footer>

    <script>
    async function fetchPrice() {
        const timeSlotId = document.getElementById('time_slot_id').value;
        const pricingProfileId = document.getElementById('pricing_profile_id').value;
        const date = document.querySelector('input[name="date"]').value;
        const priceDisplay = document.getElementById('price_display');

        if (!timeSlotId || !pricingProfileId || !date) {
            priceDisplay.textContent = '\u2014';
            return;
        }

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('room_id', '{{ $room->id }}');
        formData.append('time_slot_id', timeSlotId);
        formData.append('pricing_profile_id', pricingProfileId);
        formData.append('date', date);

        try {
            const res = await fetch('{{ route('reservation.price') }}', { method: 'POST', body: formData });
            const data = await res.json();
            priceDisplay.textContent = data.price ? Number(data.price).toFixed(2) : '\u2014';
        } catch (e) {
            priceDisplay.textContent = '\u2014';
        }
    }

    ['time_slot_id', 'pricing_profile_id'].forEach(id =>
        document.getElementById(id).addEventListener('change', fetchPrice)
    );
    document.querySelector('input[name="date"]').addEventListener('change', fetchPrice);

    document.getElementById('reserveBtn').addEventListener('click', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('reserveBtn');
        const loader = document.getElementById('loading');

        if (btn.dataset.locked) return;
        btn.dataset.locked = '1';
        btn.disabled = true;
        btn.classList.add('opacity-60');
        loader.classList.remove('hidden');

        const form = document.getElementById('reservationForm');
        const formData = new FormData(form);

        try {
            const res = await fetch(form.action, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            });
            const data = await res.json();

            if (data.checkout_url) {
                window.location.href = data.checkout_url;
            } else {
                alert(data.message || 'Erreur lors de la création du paiement');
                btn.disabled = false;
                btn.dataset.locked = '';
                btn.classList.remove('opacity-60');
                loader.classList.add('hidden');
            }
        } catch (err) {
            alert('Erreur réseau');
            btn.disabled = false;
            btn.dataset.locked = '';
            btn.classList.remove('opacity-60');
            loader.classList.add('hidden');
        }
    });
    </script>

</body>
</html>

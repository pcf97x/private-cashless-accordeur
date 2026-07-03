@extends('layouts.public')

@section('title', 'Réserver une salle — L\'Accordeur')
@section('meta_description', 'Réservez une salle de réunion ou un espace événementiel à L\'Accordeur, Cayenne. Paiement en ligne sécurisé.')

@section('content')

    <main class="max-w-6xl mx-auto px-6 py-10">

        {{-- Hero --}}
        <div class="text-center mb-12">
            <h1 class="text-3xl sm:text-4xl font-display font-bold text-gray-900">Réservez votre salle</h1>
            <p class="text-gray-500 mt-3 max-w-xl mx-auto">Choisissez parmi nos espaces au sein de L'Accordeur, Pôle Associatif de Guyane. Réservation en ligne avec paiement sécurisé.</p>
        </div>

        {{-- Room cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-7">
            @foreach($rooms as $room)
                <div class="card overflow-hidden flex flex-col hover:-translate-y-1 transition-all duration-300">
                    {{-- Photo --}}
                    <div class="h-52 bg-gray-100 relative overflow-hidden">
                        @if($room->image)
                            <img src="{{ asset('storage/' . $room->image) }}" alt="{{ $room->name }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-accordeur-50 to-accordeur-100">
                                <svg class="w-16 h-16 text-accordeur-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                        @endif
                    </div>

                    {{-- Details --}}
                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="font-display font-bold text-gray-900 text-xl mb-3">{{ $room->name }}</h3>

                        @if($room->description)
                            <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $room->description }}</p>
                        @endif

                        <div class="space-y-2 text-sm text-gray-600">
                            @if($room->location)
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-accordeur-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span>{{ $room->location }}</span>
                                </div>
                            @endif

                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-accordeur-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span><strong>Capacité :</strong> {{ $room->capacity }} {{ $room->capacity > 1 ? 'Personnes' : 'Personne' }}</span>
                            </div>

                            @php $priceRange = $room->price_range; @endphp
                            @if($priceRange)
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-accordeur-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span><strong>Prix :</strong> {{ $priceRange }}</span>
                                </div>
                            @endif

                            @if($room->equipments)
                                <div class="flex items-start gap-2.5">
                                    <svg class="w-4 h-4 text-accordeur-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span><strong>Équipement :</strong> {{ $room->equipments }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- CTA --}}
                        <div class="mt-auto pt-5">
                            <a href="{{ route('reservation.show', $room) }}" class="btn-primary w-full justify-center !py-3 text-sm">
                                Sélectionner
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </main>

@endsection

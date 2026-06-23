@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Back link --}}
    <a href="{{ route('admin.reservations.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-accordeur-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour aux réservations
    </a>

    <div class="page-header flex items-center justify-between">
        <div>
            <h1>Réservation #{{ $reservation->id }}</h1>
            <p>Créée le {{ $reservation->created_at->format('d/m/Y à H:i') }}</p>
        </div>
        <div>
            @if($reservation->status === 'paid')
                <span class="badge badge-success text-sm !px-4 !py-1.5">Payée</span>
            @elseif($reservation->status === 'cancelled')
                <span class="badge badge-danger text-sm !px-4 !py-1.5">Annulée</span>
            @else
                <span class="badge badge-warning text-sm !px-4 !py-1.5">En attente</span>
            @endif
        </div>
    </div>

    {{-- Details card --}}
    <div class="card p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 pb-2 border-b border-gray-100">Réservation</h3>
                <div>
                    <div class="text-xs text-gray-500">Salle</div>
                    <div class="font-semibold text-gray-900">{{ $reservation->room->name ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500">Date</div>
                    <div class="font-semibold text-gray-900">{{ $reservation->date->format('d/m/Y') }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500">Créneau</div>
                    <div class="font-semibold text-gray-900">{{ $reservation->timeSlot->start_time }} &rarr; {{ $reservation->timeSlot->end_time }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500">Prix</div>
                    <div class="text-xl font-bold text-accordeur-600">{{ number_format($reservation->price, 2, ',', ' ') }} &euro;</div>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 pb-2 border-b border-gray-100">Client</h3>
                <div>
                    <div class="text-xs text-gray-500">Nom</div>
                    <div class="font-semibold text-gray-900">{{ $reservation->name }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500">Email</div>
                    <div class="text-accordeur-600">{{ $reservation->email }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500">Téléphone</div>
                    <div class="text-gray-900">{{ $reservation->phone ?? '—' }}</div>
                </div>
                @if($reservation->stripe_session_id)
                <div>
                    <div class="text-xs text-gray-500">Session Stripe</div>
                    <div class="text-xs text-gray-400 font-mono break-all">{{ $reservation->stripe_session_id }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="card p-6">
        <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 pb-3 mb-4 border-b border-gray-100">Actions</h3>
        <div class="flex flex-wrap gap-3">
            <form method="POST" action="{{ route('admin.reservations.resendEmail', $reservation) }}">
                @csrf
                <button type="submit" class="btn-outline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Renvoyer l'email
                </button>
            </form>

            @if ($reservation->status === 'paid')
                <form method="POST" action="{{ route('admin.reservations.cancel', $reservation) }}" onsubmit="return confirm('Confirmer l\'annulation et le remboursement Stripe ?');">
                    @csrf
                    <button type="submit" class="btn bg-red-50 text-red-600 hover:bg-red-100 focus:ring-red-500 border border-red-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Annuler & rembourser
                    </button>
                </form>
            @endif
        </div>
    </div>

</div>
@endsection

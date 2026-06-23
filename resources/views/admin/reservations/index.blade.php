@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="page-header flex items-center justify-between">
        <div>
            <h1>Réservations</h1>
            <p>Toutes les réservations de salles</p>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Salle</th>
                    <th>Date</th>
                    <th>Créneau</th>
                    <th>Client</th>
                    <th>Email</th>
                    <th class="text-right">Prix</th>
                    <th class="text-center">Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $r)
                    <tr>
                        <td>
                            <a href="{{ route('admin.reservations.show', $r) }}" class="text-accordeur-600 font-semibold hover:text-accordeur-800 transition-colors">
                                #{{ $r->id }}
                            </a>
                        </td>
                        <td class="font-medium text-gray-900">{{ $r->room->name ?? '-' }}</td>
                        <td>{{ $r->date->format('d/m/Y') }}</td>
                        <td>
                            <span class="text-gray-700">{{ $r->start_at->format('H:i') }}</span>
                            <span class="text-gray-400 mx-1">&rarr;</span>
                            <span class="text-gray-700">{{ $r->end_at->format('H:i') }}</span>
                        </td>
                        <td class="font-medium text-gray-900">{{ $r->name }}</td>
                        <td>
                            <span class="text-accordeur-600">{{ $r->email }}</span>
                        </td>
                        <td class="text-right font-semibold text-gray-900">
                            {{ number_format($r->price, 2, ',', ' ') }} &euro;
                        </td>
                        <td class="text-center">
                            @if($r->status === 'paid')
                                <span class="badge badge-success">Payée</span>
                            @elseif($r->status === 'cancelled')
                                <span class="badge badge-danger">Annulée</span>
                            @else
                                <span class="badge badge-warning">En attente</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-gray-400 py-12">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Aucune réservation
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection

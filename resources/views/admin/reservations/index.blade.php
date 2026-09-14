@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="page-header flex items-center justify-between">
        <div>
            <h1>Réservations</h1>
            <p>Toutes les réservations de salles</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.reservations.import.form') }}" class="btn-outline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                Importer CSV
            </a>
            <a href="{{ route('admin.reservations.create') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Nouvelle réservation
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[180px]">
                <label class="form-label">Recherche</label>
                <input type="text" name="search" class="form-input !py-2 !text-sm" placeholder="Client, email, evenement, salle..." value="{{ request('search') }}">
            </div>
            <div>
                <label class="form-label">Salle</label>
                <select name="room_id" class="form-input !py-2 !text-sm">
                    <option value="">Toutes</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Statut</label>
                <select name="status" class="form-input !py-2 !text-sm">
                    <option value="">Tous</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Payee</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="devis" {{ request('status') === 'devis' ? 'selected' : '' }}>Devis</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulee</option>
                </select>
            </div>
            <div>
                <label class="form-label">Du</label>
                <input type="date" name="date_from" class="form-input !py-2 !text-sm" value="{{ request('date_from') }}">
            </div>
            <div>
                <label class="form-label">Au</label>
                <input type="date" name="date_to" class="form-input !py-2 !text-sm" value="{{ request('date_to') }}">
            </div>
            <button type="submit" class="btn-primary !py-2 !text-sm">Filtrer</button>
            @if(request()->hasAny(['search', 'room_id', 'status', 'date_from', 'date_to']))
                <a href="{{ route('admin.reservations.index') }}" class="btn-ghost !py-2 !text-sm">Reset</a>
            @endif
        </form>
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
                    <th class="text-right">Actions</th>
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
                            @elseif($r->status === 'devis')
                                <span class="badge" style="background-color: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;">Devis</span>
                            @else
                                <span class="badge badge-warning">En attente</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center justify-end">
                                <a href="{{ route('admin.reservations.show', $r) }}" class="btn-outline !py-1 !px-2.5 !text-xs !rounded-lg">Voir</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-gray-400 py-12">
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

@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="page-header">
        <h1>Pointage & Accès</h1>
        <p>Tous les passes visiteurs et réservations</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="stat-card">
            <div class="stat-icon bg-accordeur-50">
                <svg class="w-5 h-5 text-accordeur-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
            </div>
            <div class="stat-value">{{ $checkins->count() }}</div>
            <div class="stat-label">Total passes</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-emerald-50">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="stat-value">{{ $checkins->whereNotNull('entry_at')->whereNull('exit_at')->count() }}</div>
            <div class="stat-label">Présents</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-amber-50">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="stat-value">{{ $checkins->whereNull('entry_at')->count() }}</div>
            <div class="stat-label">En attente</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-red-50">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            </div>
            <div class="stat-value">{{ $checkins->whereNotNull('exit_at')->count() }}</div>
            <div class="stat-label">Sortis</div>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Visiteur</th>
                    <th>Motif</th>
                    <th>Code Weezevent</th>
                    <th>Entrée</th>
                    <th>Sortie</th>
                    <th>Statut</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($checkins as $checkin)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-accordeur-50 flex items-center justify-center text-accordeur-600 text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($checkin->firstname, 0, 1)) }}{{ strtoupper(substr($checkin->lastname, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $checkin->firstname }} {{ $checkin->lastname }}</div>
                                    <div class="text-xs text-gray-500">{{ $checkin->email ?? '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($checkin->purpose === 'resident')
                                <span class="badge badge-info">Résident</span>
                            @elseif($checkin->purpose === 'visiteur')
                                <span class="badge badge-success">Visiteur</span>
                            @elseif(str_starts_with($checkin->purpose ?? '', 'Réservation'))
                                <span class="badge badge-warning">{{ $checkin->purpose }}</span>
                            @else
                                <span class="badge" style="background:#f3f4f6;color:#6b7280;">{{ $checkin->purpose ?? '—' }}</span>
                            @endif
                        </td>
                        <td>
                            @if($checkin->weez_ticket_code)
                                <code class="text-xs bg-gray-100 px-2 py-1 rounded font-mono">{{ $checkin->weez_ticket_code }}</code>
                            @else
                                <span class="text-xs text-gray-400">Non généré</span>
                            @endif
                        </td>
                        <td>
                            @if($checkin->entry_at)
                                <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($checkin->entry_at)->format('H:i') }}</span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td>
                            @if($checkin->exit_at)
                                <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($checkin->exit_at)->format('H:i') }}</span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td>
                            @if($checkin->entry_at && !$checkin->exit_at)
                                <span class="badge badge-success">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Présent
                                </span>
                            @elseif($checkin->exit_at)
                                <span class="badge badge-danger">Sorti</span>
                            @else
                                <span class="badge badge-warning">En attente</span>
                            @endif
                        </td>
                        <td class="text-sm text-gray-500">
                            {{ $checkin->created_at?->format('d/m/Y H:i') ?? '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-400 py-12">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                            Aucun pass enregistré
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection

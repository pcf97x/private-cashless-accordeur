@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="page-header">
        <h1>Pointage visiteurs</h1>
        <p>Suivi des entrées et sorties en temps réel</p>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Société</th>
                    <th>Motif</th>
                    <th>Entrée</th>
                    <th>Sortie</th>
                    <th>Statut</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($scans as $row)
                    <tr>
                        <td class="font-medium text-gray-900">{{ $row->lastname ?? '—' }}</td>
                        <td>{{ $row->firstname ?? '—' }}</td>
                        <td>{{ $row->company ?? '—' }}</td>
                        <td>{{ $row->purpose ?? '—' }}</td>
                        <td>
                            {{ $row->entry_at ? \Carbon\Carbon::parse($row->entry_at)->format('H:i') : '—' }}
                        </td>
                        <td>
                            {{ $row->exit_at ? \Carbon\Carbon::parse($row->exit_at)->format('H:i') : '—' }}
                        </td>
                        <td>
                            @if($row->entry_at && !$row->exit_at)
                                <span class="badge badge-success">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Présent
                                </span>
                            @elseif($row->exit_at)
                                <span class="badge badge-danger">Sorti</span>
                            @else
                                <span class="badge badge-warning">En attente</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <a href="{{ route('checkins.edit', $row->weez_ticket_code) }}" class="btn-outline !py-1.5 !px-3 !text-xs !rounded-lg">
                                Compléter
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-gray-400 py-12">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            Aucun scan enregistré
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection

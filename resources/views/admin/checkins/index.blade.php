@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Pointage visiteurs</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Société</th>
                <th>Motif</th>
                <th>Entrée</th>
                <th>Sortie</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

            @forelse ($scans as $row)
                <tr>
                    <td>{{ $row->lastname ?? '—' }}</td>
                    <td>{{ $row->firstname ?? '—' }}</td>
                    <td>{{ $row->company ?? '—' }}</td>
                    <td>{{ $row->purpose ?? '—' }}</td>

                    <td>
                        {{ $row->entry_at
                            ? \Carbon\Carbon::parse($row->entry_at)->format('H:i')
                            : '—' }}
                    </td>

                    <td>
                        {{ $row->exit_at
                            ? \Carbon\Carbon::parse($row->exit_at)->format('H:i')
                            : '—' }}
                    </td>

                    <td>
                        @if($row->entry_at && !$row->exit_at)
                            🟢 Présent
                        @elseif($row->exit_at)
                            🔴 Sorti
                        @else
                            ⏳ En attente
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('checkins.edit', $row->weez_ticket_code) }}"
                           class="btn btn-sm btn-outline-primary">
                            ✏️ Compléter
                        </a>
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        Aucun scan enregistré
                    </td>
                </tr>
            @endforelse

        </tbody>
    </table>
</div>
@endsection

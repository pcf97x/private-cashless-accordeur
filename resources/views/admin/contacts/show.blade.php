@extends('layouts.app')

@section('content')
<div class="container">

    <a href="{{ route('admin.contacts.index') }}" class="mb-3 d-inline-block">
        ← Retour aux contacts
    </a>

    <h1 class="mb-3">
        {{ $contact->firstname }} {{ $contact->lastname }}
    </h1>

    {{-- INFOS CONTACT --}}
    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Société :</strong> {{ $contact->company ?? '—' }}</p>
            <p><strong>Email :</strong> {{ $contact->email }}</p>
            <p><strong>Téléphone :</strong> {{ $contact->phone ?? '—' }}</p>
            <p><strong>Nombre de visites :</strong> {{ $contact->checkins->count() }}</p>
        </div>
    </div>

    {{-- HISTORIQUE --}}
    <h3>Historique des passages</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Entrée</th>
                <th>Sortie</th>
                <th>Motif</th>
                <th>QR Code</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
        @forelse($contact->checkins as $checkin)
            <tr>
                <td>{{ $checkin->scan_date?->format('d/m/Y') ?? '—' }}</td>

                <td>{{ $checkin->entry_at?->format('H:i') ?? '—' }}</td>

                <td>{{ $checkin->exit_at?->format('H:i') ?? '—' }}</td>

                <td>{{ $checkin->purpose ?? '—' }}</td>

                <td>
                    @if($checkin->qr_token)
                        <a href="{{ url('/admin/checkins/scan/'.$checkin->qr_token) }}">
                            🔳 Scanner
                        </a>
                    @else
                        —
                    @endif
                </td>

                <td>
                    @if($checkin->exit_at)
                        🔴 Sorti
                    @else
                        🟢 Présent
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">Aucun passage enregistré</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{-- ACTIONS FUTURES --}}
    <div class="mt-4">
        <h4>Actions</h4>
        <ul>
            <li>✉️ Envoyer email (à venir)</li>
            <li>📲 Envoyer SMS (à venir)</li>
            <li>🔁 Renvoyer QR Code</li>
        </ul>
    </div>

</div>
@endsection

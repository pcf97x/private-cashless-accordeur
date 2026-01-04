@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Contacts</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Société</th>
                <th>Email</th>
                <th>QR Code</th>
                <th>Nb visites</th>
                <th>Dernière venue</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($contacts as $contact)
            <tr>
                <td>{{ $contact->lastname }}</td>
                <td>{{ $contact->firstname }}</td>
                <td>{{ $contact->company ?? '—' }}</td>
                <td>{{ $contact->email }}</td>

                {{-- QR CODE --}}
                <td>
                    @if($contact->last_qr_token)
                        <a href="{{ url('/admin/checkins/scan/'.$contact->last_qr_token) }}">
                            🔳 Scanner
                        </a>
                    @else
                        —
                    @endif
                </td>

                <td>{{ $contact->checkins_count }}</td>

                <td>
                    {{ $contact->last_entry_at
                        ? \Carbon\Carbon::parse($contact->last_entry_at)->format('d/m/Y H:i')
                        : '—'
                    }}
                </td>

                <td>
                    <a href="{{ route('admin.contacts.show', $contact) }}">👁 Historique</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">Aucun contact</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection

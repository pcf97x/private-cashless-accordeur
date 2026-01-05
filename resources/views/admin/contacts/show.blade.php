@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 space-y-6">

    <a href="{{ route('admin.contacts.index') }}" class="text-sm text-blue-600 hover:underline">
        ← Retour aux contacts
    </a>

    {{-- FICHE CONTACT --}}
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">
            {{ $contact->firstname }} {{ $contact->lastname }}
        </h1>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><strong>Société :</strong> {{ $contact->company ?? '—' }}</div>
            <div><strong>Email :</strong> {{ $contact->email ?? '—' }}</div>
            <div><strong>Téléphone :</strong> {{ $contact->phone ?? '—' }}</div>
            <div><strong>Nombre de visites :</strong> {{ $checkins->count() }}</div>
        </div>
    </div>

    {{-- QR CODE --}}
    @php
        $lastCheckin = $checkins->first();
    @endphp

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="font-semibold mb-3">QR Code</h2>

        @if($lastCheckin && $lastCheckin->qr_token)
            <img
                src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $lastCheckin->qr_token }}"
                alt="QR Code"
                class="border p-2"
            >
            <p class="text-xs text-gray-500 mt-2">
                Token : {{ $lastCheckin->qr_token }}
            </p>
        @else
            <p class="text-sm text-gray-500">Aucun QR code disponible</p>
        @endif
    </div>

    {{-- HISTORIQUE --}}
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="font-semibold mb-4">Historique des passages</h2>

        @if($checkins->isEmpty())
            <p class="text-sm text-gray-500">Aucun passage enregistré</p>
        @else
            <table class="w-full text-sm border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">Entrée</th>
                        <th class="p-2 text-left">Sortie</th>
                        <th class="p-2 text-left">Motif</th>
                        <th class="p-2 text-left">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($checkins as $checkin)
                        <tr class="border-t">
                            <td class="p-2">{{ $checkin->entry_at ?? '—' }}</td>
                            <td class="p-2">{{ $checkin->exit_at ?? '—' }}</td>
                            <td class="p-2">{{ $checkin->purpose ?? '—' }}</td>
                            <td class="p-2">
                                @if($checkin->exit_at)
                                    <span class="text-red-600">Sorti</span>
                                @else
                                    <span class="text-green-600">Présent</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- ACTIONS FUTURES --}}
    <div class="flex gap-4">
        <button class="btn-secondary" disabled>📧 Envoyer email</button>
        <button class="btn-secondary" disabled>📱 Envoyer SMS</button>
        <button class="btn-primary" disabled>🔁 Renvoyer QR Code</button>
    </div>

</div>
@endsection

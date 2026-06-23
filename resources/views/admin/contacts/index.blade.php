@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="page-header">
        <h1>Contacts</h1>
        <p>Base de données de tous les participants enregistrés</p>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Société</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th class="text-center">QR</th>
                    <th class="text-center">Visites</th>
                    <th class="text-center">Dernière venue</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $contact)
                    <tr>
                        <td class="font-medium text-gray-900">
                            {{ $contact->lastname }} {{ $contact->firstname }}
                        </td>
                        <td>{{ $contact->company ?? '—' }}</td>
                        <td>
                            @if($contact->email)
                                <span class="text-accordeur-600">{{ $contact->email }}</span>
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $contact->phone ?? '—' }}</td>
                        <td class="text-center">
                            @if($contact->lastCheckin)
                                <span class="badge badge-info">QR</span>
                            @else
                                —
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="font-semibold text-gray-900">{{ $contact->checkins_count }}</span>
                        </td>
                        <td class="text-center text-gray-500">
                            {{ optional($contact->lastCheckin?->entry_at)->format('d/m H:i') ?? '—' }}
                        </td>
                        <td class="text-right">
                            <a href="{{ route('admin.contacts.show', $contact) }}" class="btn-outline !py-1.5 !px-3 !text-xs !rounded-lg">
                                Historique
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-gray-400 py-12">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Aucun contact
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection

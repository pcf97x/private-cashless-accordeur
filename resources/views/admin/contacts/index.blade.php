@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200">

    <table class="w-full text-sm">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3 text-left">Nom</th>
                <th class="px-4 py-3">Société</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">Téléphone</th>
                <th class="px-4 py-3">QR</th>
                <th class="px-4 py-3">Visites</th>
                <th class="px-4 py-3">Dernière venue</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>

        <tbody class="divide-y">
        @forelse($contacts as $contact)
            <tr class="hover:bg-gray-50">

                <td class="px-4 py-3 font-medium">
                    {{ $contact->lastname }} {{ $contact->firstname }}
                </td>

                <td class="px-4 py-3">
                    {{ $contact->company ?? '—' }}
                </td>

                <td class="px-4 py-3">
                    {{ $contact->email ?? '—' }}
                </td>

                <td class="px-4 py-3">
                    {{ $contact->phone ?? '—' }}
                </td>

                <td class="px-4 py-3 text-center">
                    @if($contact->lastCheckin)
                        <span class="text-xs bg-gray-200 px-2 py-1 rounded">
                            QR
                        </span>
                    @else
                        —
                    @endif
                </td>

                <td class="px-4 py-3 text-center">
                    <span class="font-semibold">
                        {{ $contact->checkins_count }}
                    </span>
                </td>

                <td class="px-4 py-3 text-center text-gray-500">
                    {{ optional($contact->lastCheckin?->entry_at)->format('d/m H:i') ?? '—' }}
                </td>

                <td class="px-4 py-3 text-right space-x-2">
                    <a href="{{ route('admin.contacts.show', $contact) }}"
                       class="text-blue-600 hover:underline text-xs">
                        Historique
                    </a>
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="8" class="px-4 py-6 text-center text-gray-400">
                    Aucun contact
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

@endsection

@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="page-header flex items-center justify-between">
        <div>
            <h1>Créneaux horaires</h1>
            <p>Gestion des plages horaires de réservation</p>
        </div>
        <a href="{{ route('admin.time-slots.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Nouveau créneau
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    @if(session('warning'))
        <div class="mb-6 p-4 rounded-xl bg-yellow-50 border border-yellow-200 text-yellow-700 text-sm">{{ session('warning') }}</div>
    @endif

    @if($timeSlots->count())
        <div class="table-container">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">Ordre</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">Label</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">Horaires</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">Code</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">Statut</th>
                        <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wider px-5 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($timeSlots as $slot)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-accordeur-50 text-accordeur-700 text-sm font-semibold">{{ $slot->order_index }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-display font-semibold text-gray-900">{{ $slot->label }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-accordeur-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $slot->start_time }} → {{ $slot->end_time }}
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <code class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-md font-mono">{{ $slot->code }}</code>
                            </td>
                            <td class="px-5 py-4">
                                @if($slot->active)
                                    <span class="badge badge-success">Actif</span>
                                @else
                                    <span class="badge badge-danger">Inactif</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.time-slots.edit', $slot) }}" class="btn-outline !py-1.5 !px-3 !text-xs !rounded-lg">
                                        Modifier
                                    </a>
                                    <form method="POST" action="{{ route('admin.time-slots.destroy', $slot) }}" onsubmit="return confirm('Supprimer ce créneau ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn !py-1.5 !px-3 !text-xs !rounded-lg bg-red-50 text-red-600 hover:bg-red-100 border border-red-200">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="card p-12 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <h3 class="text-lg font-display font-bold text-gray-900 mb-1">Aucun créneau</h3>
            <p class="text-gray-500 mb-6">Commencez par créer votre premier créneau horaire</p>
            <a href="{{ route('admin.time-slots.create') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Créer un créneau
            </a>
        </div>
    @endif

</div>
@endsection

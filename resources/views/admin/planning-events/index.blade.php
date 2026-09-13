@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="page-header flex items-center justify-between">
        <div>
            <h1>Services & Evenements</h1>
            <p>Annonces affichees dans le planning public</p>
        </div>
        <a href="{{ route('admin.planning-events.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Nouvel evenement
        </a>
    </div>

    @if($events->count())
        <div class="card overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="w-4"></th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Evenement</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Date</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Horaires</th>
                        <th class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Statut</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                        <tr class="border-t border-gray-50 hover:bg-accordeur-50/30 transition-colors">
                            <td class="pl-4 py-4">
                                <div class="w-3 h-3 rounded-full" style="background-color: {{ $event->color }}"></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $event->title }}</div>
                                @if($event->description)
                                    <div class="text-xs text-gray-500 truncate max-w-xs">{{ $event->description }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ $event->date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-gray-700">
                                @if($event->start_time && $event->end_time)
                                    {{ \Carbon\Carbon::parse($event->start_time)->format('H\hi') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('H\hi') }}
                                @else
                                    Toute la journee
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($event->active)
                                    <span class="badge badge-success">Actif</span>
                                @else
                                    <span class="badge badge-danger">Inactif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.planning-events.edit', $event) }}" class="btn-outline !py-1.5 !px-3 !text-xs !rounded-lg">Modifier</a>
                                    <form method="POST" action="{{ route('admin.planning-events.destroy', $event) }}" onsubmit="return confirm('Supprimer cet evenement ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn !py-1.5 !px-3 !text-xs !rounded-lg bg-red-50 text-red-600 hover:bg-red-100 border border-red-200">Supprimer</button>
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
            <h3 class="text-lg font-display font-bold text-gray-900 mb-1">Aucun evenement</h3>
            <p class="text-gray-500 mb-6">Ajoutez des services ou evenements pour les afficher dans le planning public</p>
            <a href="{{ route('admin.planning-events.create') }}" class="btn-primary">Creer un evenement</a>
        </div>
    @endif

</div>
@endsection

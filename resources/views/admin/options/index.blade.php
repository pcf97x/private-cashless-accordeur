@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="page-header flex items-center justify-between">
        <div>
            <h1>Options de réservation</h1>
            <p>Petit déjeuner, repas, et autres extras proposés lors de la réservation</p>
        </div>
        <a href="{{ route('admin.options.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Nouvelle option
        </a>
    </div>

    @if($options->count())
        <div class="card overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Option</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Description</th>
                        <th class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Prix</th>
                        <th class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Statut</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($options as $option)
                        <tr class="border-t border-gray-50 hover:bg-accordeur-50/30 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $option->name }}</td>
                            <td class="px-6 py-4 text-gray-500 max-w-xs truncate">{{ $option->description ?? '—' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold text-accordeur-600">{{ number_format($option->price, 2) }} &euro;</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($option->active)
                                    <span class="badge badge-success">Actif</span>
                                @else
                                    <span class="badge badge-danger">Inactif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.options.edit', $option) }}" class="btn-outline !py-1.5 !px-3 !text-xs !rounded-lg">Modifier</a>
                                    <form method="POST" action="{{ route('admin.options.destroy', $option) }}" onsubmit="return confirm('Supprimer cette option ?');">
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
            <h3 class="text-lg font-display font-bold text-gray-900 mb-1">Aucune option</h3>
            <p class="text-gray-500 mb-6">Ajoutez des extras comme le petit déjeuner ou le repas</p>
            <a href="{{ route('admin.options.create') }}" class="btn-primary">Créer une option</a>
        </div>
    @endif

</div>
@endsection

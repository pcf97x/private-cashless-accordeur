@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="page-header flex items-center justify-between">
        <div>
            <h1>Profils tarifaires</h1>
            <p>Gérez les types de clients (Abonné, Adhérent, Résident, etc.)</p>
        </div>
        <a href="{{ route('admin.pricing-profiles.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Nouveau profil
        </a>
    </div>

    @if($profiles->count())
        <div class="card overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="w-10"></th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Code</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Libelle</th>
                        <th class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Statut</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody id="sortable-profiles">
                    @foreach($profiles as $profile)
                        <tr class="border-t border-gray-50 hover:bg-accordeur-50/30 transition-colors" data-id="{{ $profile->id }}">
                            <td class="pl-4 py-4 cursor-grab drag-handle text-gray-300 hover:text-gray-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-100 text-xs font-mono font-bold text-gray-600">{{ $profile->code }}</span>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $profile->label }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($profile->active)
                                    <span class="badge badge-success">Actif</span>
                                @else
                                    <span class="badge badge-danger">Inactif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.pricing-profiles.edit', $profile) }}" class="btn-outline !py-1.5 !px-3 !text-xs !rounded-lg">Modifier</a>
                                    <form method="POST" action="{{ route('admin.pricing-profiles.destroy', $profile) }}" onsubmit="return confirm('Supprimer ce profil ? Les tarifs associes seront aussi supprimes.');">
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
            <h3 class="text-lg font-display font-bold text-gray-900 mb-1">Aucun profil tarifaire</h3>
            <p class="text-gray-500 mb-6">Commencez par creer vos profils clients</p>
            <a href="{{ route('admin.pricing-profiles.create') }}" class="btn-primary">Creer un profil</a>
        </div>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const el = document.getElementById('sortable-profiles');
    if (!el) return;

    Sortable.create(el, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'bg-accordeur-50',
        onEnd: function() {
            const ids = [...el.querySelectorAll('tr[data-id]')].map(r => parseInt(r.dataset.id));
            fetch('{{ route("admin.pricing-profiles.reorder") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ ids }),
            });
        }
    });
});
</script>
@endsection

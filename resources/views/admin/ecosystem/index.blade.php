@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="page-header flex items-center justify-between">
        <div>
            <h1>Écosystème</h1>
            <p>Associations et partenaires des bureaux associatifs</p>
        </div>
        <a href="{{ route('admin.ecosystem.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Nouveau partenaire
        </a>
    </div>

    @if($partners->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($partners as $partner)
                <div class="card overflow-hidden flex flex-col">
                    {{-- Logo --}}
                    <div class="h-44 bg-gray-100 relative overflow-hidden">
                        @if($partner->logo)
                            <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->name }}" class="w-full h-full object-contain p-6 bg-white">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-accordeur-50 to-accordeur-100">
                                <svg class="w-12 h-12 text-accordeur-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                        @endif
                        <div class="absolute top-3 right-3">
                            @if($partner->active)
                                <span class="badge badge-success">Actif</span>
                            @else
                                <span class="badge badge-danger">Inactif</span>
                            @endif
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="font-display font-bold text-gray-900 text-lg mb-2">{{ $partner->name }}</h3>

                        @if($partner->description)
                            <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $partner->description }}</p>
                        @endif

                        <div class="space-y-1.5 text-sm text-gray-600 mb-4">
                            @if($partner->contact_name)
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-accordeur-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span class="truncate">{{ $partner->contact_name }}</span>
                                </div>
                            @endif
                            @if($partner->contact_email)
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-accordeur-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <span class="truncate">{{ $partner->contact_email }}</span>
                                </div>
                            @endif
                            @if($partner->website)
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-accordeur-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                    <a href="{{ $partner->website }}" target="_blank" class="truncate text-accordeur-600 hover:underline">{{ $partner->website }}</a>
                                </div>
                            @endif
                        </div>

                        <div class="mt-auto flex items-center gap-2 pt-4 border-t border-gray-100">
                            <a href="{{ route('admin.ecosystem.edit', $partner) }}" class="btn-outline !py-1.5 !px-3 !text-xs !rounded-lg flex-1 text-center">
                                Modifier
                            </a>
                            <form method="POST" action="{{ route('admin.ecosystem.destroy', $partner) }}" onsubmit="return confirm('Supprimer ce partenaire ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn !py-1.5 !px-3 !text-xs !rounded-lg bg-red-50 text-red-600 hover:bg-red-100 border border-red-200">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card p-12 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <h3 class="text-lg font-display font-bold text-gray-900 mb-1">Aucun partenaire</h3>
            <p class="text-gray-500 mb-6">Commencez par ajouter les associations de l'écosystème</p>
            <a href="{{ route('admin.ecosystem.create') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Ajouter un partenaire
            </a>
        </div>
    @endif

</div>
@endsection

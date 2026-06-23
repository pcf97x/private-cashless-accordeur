@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- Page header --}}
    <div class="page-header">
        <h1>Tableau de bord</h1>
        <p>Bienvenue, {{ Auth::user()->name }}. Voici l'activité de L'Accordeur.</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div class="stat-icon bg-emerald-50">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="badge badge-success">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    En direct
                </span>
            </div>
            <div class="stat-value">{{ $presentCount }}</div>
            <div class="stat-label">Présents maintenant</div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div class="stat-icon bg-accordeur-50">
                    <svg class="w-5 h-5 text-accordeur-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                </div>
            </div>
            <div class="stat-value">{{ $todayCount }}</div>
            <div class="stat-label">Entrées aujourd'hui</div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div class="stat-icon bg-violet-50">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <div class="stat-value">{{ $contactsCount }}</div>
            <div class="stat-label">Contacts enregistrés</div>
        </div>

    </div>

    {{-- Quick actions --}}
    <div class="page-header">
        <h1 class="!text-lg">Actions rapides</h1>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        <a href="{{ url('/acces') }}" class="action-card">
            <div class="action-icon bg-emerald-50">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </div>
            <div>
                <div class="text-sm font-semibold text-gray-900">Nouvelle entrée</div>
                <div class="text-xs text-gray-500">Créer un visiteur</div>
            </div>
        </a>

        <a href="{{ url('/admin/checkins') }}" class="action-card">
            <div class="action-icon bg-accordeur-50">
                <svg class="w-6 h-6 text-accordeur-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            </div>
            <div>
                <div class="text-sm font-semibold text-gray-900">Pointage</div>
                <div class="text-xs text-gray-500">Scanner & gérer</div>
            </div>
        </a>

        <a href="{{ route('admin.contacts.index') }}" class="action-card">
            <div class="action-icon bg-violet-50">
                <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <div class="text-sm font-semibold text-gray-900">Contacts</div>
                <div class="text-xs text-gray-500">Base participants</div>
            </div>
        </a>

        <a href="{{ url('/admin/reservations') }}" class="action-card">
            <div class="action-icon bg-rouge-50">
                <svg class="w-6 h-6 text-rouge-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <div class="text-sm font-semibold text-gray-900">Réservations</div>
                <div class="text-xs text-gray-500">Gérer les salles</div>
            </div>
        </a>

    </div>

</div>
@endsection

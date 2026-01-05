@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-6">

    {{-- TITRE --}}
    <h1 class="text-2xl font-bold mb-1">Dashboard — L’Accordeur</h1>
    <p class="text-gray-600 mb-6">
        Bienvenue 👋 Voici l’activité et les actions principales
    </p>

    {{-- CARTES ACTIONS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">

        <a href="{{ url('/acces') }}" class="dashboard-card bg-green-100">
            ➕ Nouvelle entrée
            <span>Créer un participant</span>
        </a>

        <a href="{{ url('/admin/checkins') }}" class="dashboard-card bg-blue-100">
            📋 Check-ins
            <span>Scanner & gérer</span>
        </a>

        <a href="{{ route('admin.contacts.index') }}" class="dashboard-card bg-purple-100">
            👥 Contacts
            <span>Base participants</span>
        </a>

        <div class="dashboard-card bg-gray-100 opacity-60 cursor-not-allowed">
            ✉️ Campagnes
            <span>Email & SMS (bientôt)</span>
        </div>

    </div>

    {{-- STATISTIQUES --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

        <div class="stat-card">
            🟢 Présents maintenant
            <strong>{{ $presentNow }}</strong>
        </div>

        <div class="stat-card">
            🔄 Entrées aujourd’hui
            <strong>{{ $todayEntries }}</strong>
        </div>

        <div class="stat-card">
            👥 Contacts enregistrés
            <strong>{{ $contactsCount }}</strong>
        </div>

    </div>

    {{-- ACCÈS RAPIDE --}}
    <div class="bg-white p-5 rounded shadow-sm">
        <h2 class="font-semibold mb-3">Accès rapide</h2>
        <ul class="list-disc ml-6 text-sm text-gray-700 space-y-1">
            <li><a class="text-blue-600" href="{{ url('/acces') }}">Ajouter un participant</a></li>
            <li><a class="text-blue-600" href="{{ url('/admin/checkins') }}">Scanner un QR Code</a></li>
            <li><a class="text-blue-600" href="{{ route('admin.contacts.index') }}">Voir tous les contacts</a></li>
        </ul>
    </div>

    {{-- À VENIR --}}
    <div class="mt-6 bg-yellow-50 p-4 rounded">
        <h3 class="font-semibold mb-1">🚀 À venir</h3>
        <p class="text-sm text-gray-700">
            Envoi d’emails (Brevo), SMS avec crédits, segmentation, statistiques avancées.
        </p>
    </div>

</div>
@endsection

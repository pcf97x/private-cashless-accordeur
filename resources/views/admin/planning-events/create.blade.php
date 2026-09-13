@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">

    <a href="{{ route('admin.planning-events.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-accordeur-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour aux evenements
    </a>

    <div class="page-header">
        <h1>Nouvel evenement</h1>
        <p>Creer un service ou evenement visible dans le planning public</p>
    </div>

    <div class="card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.planning-events.store') }}">
            @csrf
            @include('admin.planning-events._form')

            <div class="flex items-center gap-3 pt-6 mt-6 border-t border-gray-100">
                <button type="submit" class="btn-primary">Creer l'evenement</button>
                <a href="{{ route('admin.planning-events.index') }}" class="btn-ghost">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection

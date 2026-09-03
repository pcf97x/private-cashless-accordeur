@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-12">

    <div class="card p-8 sm:p-10 text-center">

        @if($checkin->exit_at)
            {{-- Sortie enregistrée --}}
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 mb-5">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </div>

            <h2 class="text-xl font-bold text-gray-800 mb-2">Sortie enregistrée</h2>

            <p class="text-gray-500 text-sm mb-1">
                {{ $checkin->firstname }} {{ $checkin->lastname }}
            </p>
            <p class="text-gray-400 text-sm">
                Entrée : {{ \Carbon\Carbon::parse($checkin->entry_at)->format('H:i') }} — Sortie : {{ \Carbon\Carbon::parse($checkin->exit_at)->format('H:i') }}
            </p>

        @elseif($checkin->entry_at)
            {{-- Entrée enregistrée (déjà pointé) --}}
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-100 mb-5">
                <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <h2 class="text-xl font-bold text-gray-800 mb-2">Entrée enregistrée</h2>

            <p class="text-gray-500 text-sm mb-1">
                {{ $checkin->firstname }} {{ $checkin->lastname }}
            </p>
            <p class="text-gray-400 text-sm">
                Pointé à {{ \Carbon\Carbon::parse($checkin->entry_at)->format('H:i') }}
            </p>
        @endif

        <div class="mt-8">
            <a href="{{ route('checkins.index') }}" class="btn-primary inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Retour aux pointages
            </a>
        </div>

    </div>

</div>
@endsection

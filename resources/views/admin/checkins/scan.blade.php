@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-12">

    <div class="card p-8 sm:p-10 text-center">

        @if($checkin->entry_at)
            {{-- Already checked in --}}
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-amber-100 mb-5">
                <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/>
                </svg>
            </div>

            <h2 class="text-xl font-bold text-gray-800 mb-2">Deja pointe</h2>

            <p class="text-gray-500 text-sm mb-1">
                {{ $checkin->firstname }} {{ $checkin->lastname }}
            </p>
            <p class="text-gray-400 text-sm">
                Enregistre le {{ $checkin->entry_at->format('d/m/Y') }} a {{ $checkin->entry_at->format('H:i') }}
            </p>
        @else
            {{-- New scan success --}}
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-5">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h2 class="text-xl font-bold text-gray-800 mb-2">Pointage enregistre</h2>

            <p class="text-gray-500 text-sm">
                {{ $checkin->firstname }} {{ $checkin->lastname }}
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

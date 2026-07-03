@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="page-header">
        <h1>Grille tarifaire</h1>
        <p>Definissez les prix par salle, creneau et profil</p>
    </div>

    {{-- Flash success --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-xl bg-emerald-50 border border-emerald-200 px-5 py-4 text-sm text-emerald-700">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.rates.store') }}">
        @csrf

        <div class="space-y-6">
            @foreach ($rooms as $room)
                <div class="card overflow-hidden">
                    {{-- Room header --}}
                    <div class="px-6 py-4 bg-gradient-to-r from-accordeur-50 to-white border-b border-gray-100 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-accordeur-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-accordeur-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <h2 class="font-display font-bold text-gray-900 text-lg">{{ $room->name }}</h2>
                    </div>

                    {{-- Pricing grid --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50/80">
                                <tr>
                                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 w-1/4">
                                        Creneau
                                    </th>
                                    @foreach ($profiles as $profile)
                                        <th class="px-4 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                            <span class="badge-info">{{ $profile->label }}</span>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($timeSlots as $slot)
                                    <tr class="border-t border-gray-50 hover:bg-accordeur-50/30 transition-colors duration-150">
                                        <td class="px-6 py-4">
                                            <span class="font-medium text-gray-700">{{ $slot->label }}</span>
                                        </td>

                                        @foreach ($profiles as $profile)
                                            @php
                                                $key = $room->id . '_' . $slot->id . '_' . $profile->id;
                                            @endphp
                                            <td class="px-4 py-3">
                                                <div class="relative max-w-[120px] mx-auto">
                                                    <input
                                                        type="number"
                                                        step="0.01"
                                                        min="0"
                                                        class="form-input text-right pr-8 !py-2 !text-sm"
                                                        name="rates[{{ $room->id }}][{{ $slot->id }}][{{ $profile->id }}]"
                                                        value="{{ $rates[$key]->price ?? '' }}"
                                                        placeholder="0.00"
                                                    >
                                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-medium pointer-events-none">EUR</span>
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Submit --}}
        <div class="mt-8 flex justify-end">
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Enregistrer les tarifs
            </button>
        </div>

    </form>
</div>
@endsection

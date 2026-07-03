@extends('layouts.public')

@section('title', $room->name . ' — Réservation — L\'Accordeur')
@section('meta_description', 'Réservez ' . $room->name . ' à L\'Accordeur, Cayenne. ' . $room->capacity . ' personnes, équipé et prêt pour vos événements.')

@section('content')

<div x-data="reservationCalendar()" class="min-h-screen">

    {{-- ============================================================== --}}
    {{-- ROOM HEADER                                                     --}}
    {{-- ============================================================== --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-accordeur-50/80 via-accordeur-50/30 to-white">
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
            <div class="absolute -top-24 -right-24 w-[400px] h-[400px] rounded-full bg-accordeur-100/40 blur-3xl"></div>
        </div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                <a href="{{ route('home') }}" class="hover:text-accordeur-600 transition-colors">Accueil</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('reservation.index') }}" class="hover:text-accordeur-600 transition-colors">Nos salles</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-gray-900 font-medium">{{ $room->name }}</span>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="flex items-start gap-5">
                    {{-- Room icon --}}
                    <div class="w-16 h-16 rounded-2xl bg-white shadow-card flex items-center justify-center shrink-0">
                        <svg class="w-8 h-8 text-accordeur-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <h1 class="text-3xl lg:text-4xl font-display font-extrabold text-gray-900">{{ $room->name }}</h1>
                        <div class="flex flex-wrap items-center gap-3 mt-3">
                            <span class="badge-info">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $room->capacity }} personnes
                            </span>
                            @if($room->surface_m2)
                                <span class="badge-info">{{ $room->surface_m2 }} m&sup2;</span>
                            @endif
                            @if($room->location)
                                <span class="badge-info">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    {{ $room->location }}
                                </span>
                            @endif
                        </div>
                        @if($room->equipments)
                            <div class="flex flex-wrap gap-2 mt-3">
                                @foreach(explode(',', $room->equipments) as $equip)
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-600 bg-white px-2.5 py-1 rounded-full border border-gray-100">
                                        <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        {{ trim($equip) }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Price range --}}
                @php $priceRange = $room->price_range; @endphp
                @if($priceRange)
                    <div class="bg-white rounded-2xl shadow-card p-5 text-center shrink-0">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Tarif</p>
                        <p class="text-2xl font-display font-extrabold text-accordeur-600">{{ $priceRange }}</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ============================================================== --}}
    {{-- MAIN CONTENT: CALENDAR + FORM                                   --}}
    {{-- ============================================================== --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">

        {{-- Errors --}}
        @if($errors->any())
            <div class="mb-8 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl text-sm">
                <div class="flex items-center gap-2 font-semibold mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Erreur
                </div>
                <ul class="list-disc ml-6 space-y-1">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Steps indicator --}}
        <div class="flex items-center justify-center gap-0 mb-10">
            <div class="flex items-center gap-2">
                <div :class="step >= 1 ? 'bg-accordeur-500 text-white' : 'bg-gray-200 text-gray-500'" class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-colors duration-300">1</div>
                <span class="text-sm font-semibold" :class="step >= 1 ? 'text-accordeur-600' : 'text-gray-400'">Date</span>
            </div>
            <div class="w-12 h-0.5 mx-2" :class="step >= 2 ? 'bg-accordeur-500' : 'bg-gray-200'"></div>
            <div class="flex items-center gap-2">
                <div :class="step >= 2 ? 'bg-accordeur-500 text-white' : 'bg-gray-200 text-gray-500'" class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-colors duration-300">2</div>
                <span class="text-sm font-semibold" :class="step >= 2 ? 'text-accordeur-600' : 'text-gray-400'">Créneau</span>
            </div>
            <div class="w-12 h-0.5 mx-2" :class="step >= 3 ? 'bg-accordeur-500' : 'bg-gray-200'"></div>
            <div class="flex items-center gap-2">
                <div :class="step >= 3 ? 'bg-accordeur-500 text-white' : 'bg-gray-200 text-gray-500'" class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-colors duration-300">3</div>
                <span class="text-sm font-semibold" :class="step >= 3 ? 'text-accordeur-600' : 'text-gray-400'">Réserver</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

            {{-- ============================== --}}
            {{-- LEFT: CALENDAR (3 cols)        --}}
            {{-- ============================== --}}
            <div class="lg:col-span-3 space-y-6">

                {{-- Calendar card --}}
                <div class="card overflow-hidden">
                    {{-- Calendar header --}}
                    <div class="bg-gradient-to-r from-accordeur-500 to-accordeur-600 px-6 py-4 flex items-center justify-between">
                        <button @click="prevMonth()" class="w-9 h-9 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <div class="text-center">
                            <h2 class="text-white font-display font-bold text-lg capitalize" x-text="monthName + ' ' + currentYear"></h2>
                            <button @click="goToToday()" class="text-white/70 hover:text-white text-xs font-medium transition-colors mt-0.5">Aujourd'hui</button>
                        </div>
                        <button @click="nextMonth()" class="w-9 h-9 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>

                    {{-- Day headers --}}
                    <div class="grid grid-cols-7 bg-gray-50 border-b border-gray-100">
                        <template x-for="day in ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim']" :key="day">
                            <div class="py-3 text-center text-xs font-bold uppercase tracking-wider text-gray-400" x-text="day"></div>
                        </template>
                    </div>

                    {{-- Calendar grid --}}
                    <div class="grid grid-cols-7">
                        <template x-for="(week, wi) in weeks" :key="wi">
                            <template x-for="(day, di) in week" :key="wi + '-' + di">
                                <button
                                    @click="day.date && !day.isPast && selectDay(day)"
                                    :disabled="!day.date || day.isPast"
                                    :class="{
                                        'bg-white hover:bg-accordeur-50': day.date && !day.isPast && !day.isSelected && !day.isToday,
                                        'bg-gray-50/50 text-gray-300 cursor-not-allowed': !day.date || day.isPast,
                                        'bg-accordeur-500 text-white shadow-lg shadow-accordeur-500/30 scale-105 z-10': day.isSelected,
                                        'ring-2 ring-accordeur-400 ring-inset': day.isToday && !day.isSelected,
                                    }"
                                    class="relative aspect-square flex flex-col items-center justify-center border-b border-r border-gray-100/60 transition-all duration-200 group"
                                >
                                    <span class="text-sm font-semibold" :class="day.isSelected ? 'text-white' : (day.isPast ? 'text-gray-300' : 'text-gray-700')" x-text="day.dayNum"></span>

                                    {{-- Availability dots --}}
                                    <div x-show="day.date && !day.isPast" class="flex items-center gap-0.5 mt-1">
                                        <template x-for="slot in getSlotStatus(day)" :key="slot.id">
                                            <div
                                                class="w-1.5 h-1.5 rounded-full transition-colors"
                                                :class="{
                                                    'bg-emerald-400': slot.available && !day.isSelected,
                                                    'bg-white/80': slot.available && day.isSelected,
                                                    'bg-red-400': !slot.available && !day.isSelected,
                                                    'bg-white/40': !slot.available && day.isSelected,
                                                }"
                                            ></div>
                                        </template>
                                    </div>
                                </button>
                            </template>
                        </template>
                    </div>

                    {{-- Legend --}}
                    <div class="px-6 py-3 bg-gray-50/50 border-t border-gray-100 flex items-center justify-center gap-6 text-xs text-gray-500">
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                            <span>Disponible</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 rounded-full bg-red-400"></div>
                            <span>Réservé</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-5 rounded border-2 border-accordeur-400 bg-white"></div>
                            <span>Aujourd'hui</span>
                        </div>
                    </div>
                </div>

                {{-- ============================== --}}
                {{-- TIME SLOTS (shown after date)  --}}
                {{-- ============================== --}}
                <div x-show="selectedDate" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="card overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                        <h3 class="font-display font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-accordeur-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Créneaux du <span class="text-accordeur-600" x-text="formattedSelectedDate"></span>
                        </h3>
                    </div>
                    <div class="p-4 space-y-3">
                        @foreach($timeSlots as $slot)
                            <button
                                data-slot-id="{{ $slot->id }}"
                                data-slot-label="{{ $slot->label }}"
                                data-slot-start="{{ $slot->start_time }}"
                                data-slot-end="{{ $slot->end_time }}"
                                @click="selectSlot(Number($el.dataset.slotId), $el.dataset.slotLabel, $el.dataset.slotStart, $el.dataset.slotEnd)"
                                :disabled="isSlotBooked({{ $slot->id }})"
                                :class="{
                                    'border-accordeur-500 bg-accordeur-50 ring-2 ring-accordeur-500/20': selectedSlotId === {{ $slot->id }},
                                    'border-gray-200 hover:border-accordeur-300 hover:bg-accordeur-50/30': selectedSlotId !== {{ $slot->id }} && !isSlotBooked({{ $slot->id }}),
                                    'border-gray-100 bg-gray-50 opacity-50 cursor-not-allowed': isSlotBooked({{ $slot->id }}),
                                }"
                                class="w-full flex items-center justify-between p-4 rounded-xl border-2 transition-all duration-200"
                            >
                                <div class="flex items-center gap-4">
                                    <div :class="selectedSlotId === {{ $slot->id }} ? 'bg-accordeur-500 text-white' : (isSlotBooked({{ $slot->id }}) ? 'bg-gray-200 text-gray-400' : 'bg-accordeur-50 text-accordeur-600')" class="w-11 h-11 rounded-xl flex items-center justify-center transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-semibold text-gray-900">{{ $slot->label }}</p>
                                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($slot->start_time)->format('H\\hi') }} — {{ \Carbon\Carbon::parse($slot->end_time)->format('H\\hi') }}</p>
                                    </div>
                                </div>
                                <div>
                                    <template x-if="isSlotBooked({{ $slot->id }})">
                                        <span class="badge-danger text-xs">Réservé</span>
                                    </template>
                                    <template x-if="!isSlotBooked({{ $slot->id }})">
                                        <span class="badge-success text-xs">Disponible</span>
                                    </template>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ============================== --}}
            {{-- RIGHT: BOOKING FORM (2 cols)   --}}
            {{-- ============================== --}}
            <div class="lg:col-span-2">
                <div class="lg:sticky lg:top-24 space-y-6">

                    {{-- Selection summary --}}
                    <div class="card p-6">
                        <h3 class="font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-accordeur-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Votre réservation
                        </h3>

                        <div class="space-y-3">
                            {{-- Room --}}
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-accordeur-50/50">
                                <div class="w-9 h-9 rounded-lg bg-accordeur-100 flex items-center justify-center">
                                    <svg class="w-4.5 h-4.5 text-accordeur-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-medium">Salle</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $room->name }}</p>
                                </div>
                            </div>

                            {{-- Date --}}
                            <div class="flex items-center gap-3 p-3 rounded-xl" :class="selectedDate ? 'bg-accordeur-50/50' : 'bg-gray-50 border border-dashed border-gray-200'">
                                <div class="w-9 h-9 rounded-lg flex items-center justify-center" :class="selectedDate ? 'bg-accordeur-100' : 'bg-gray-100'">
                                    <svg class="w-4.5 h-4.5" :class="selectedDate ? 'text-accordeur-600' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-medium">Date</p>
                                    <p class="text-sm font-semibold" :class="selectedDate ? 'text-gray-900' : 'text-gray-400'" x-text="selectedDate ? formattedSelectedDate : 'Sélectionnez une date'"></p>
                                </div>
                            </div>

                            {{-- Time slot --}}
                            <div class="flex items-center gap-3 p-3 rounded-xl" :class="selectedSlotId ? 'bg-accordeur-50/50' : 'bg-gray-50 border border-dashed border-gray-200'">
                                <div class="w-9 h-9 rounded-lg flex items-center justify-center" :class="selectedSlotId ? 'bg-accordeur-100' : 'bg-gray-100'">
                                    <svg class="w-4.5 h-4.5" :class="selectedSlotId ? 'text-accordeur-600' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-medium">Créneau</p>
                                    <p class="text-sm font-semibold" :class="selectedSlotId ? 'text-gray-900' : 'text-gray-400'" x-text="selectedSlotLabel || 'Sélectionnez un créneau'"></p>
                                </div>
                            </div>

                            {{-- Pricing profile --}}
                            <div x-show="selectedSlotId" x-transition class="pt-2">
                                <label class="form-label">Profil tarifaire</label>
                                <select x-model="selectedProfileId" @change="fetchPrice()" class="form-input">
                                    <option value="">Choisir...</option>
                                    @foreach($pricingProfiles as $p)
                                        <option value="{{ $p->id }}">{{ $p->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Price display --}}
                        <div x-show="price !== null" x-transition class="mt-5 bg-gradient-to-r from-accordeur-500 to-accordeur-600 rounded-xl p-4 text-center">
                            <p class="text-accordeur-100 text-xs font-semibold uppercase tracking-wider">Prix du créneau</p>
                            <p class="text-3xl font-display font-extrabold text-white mt-1">
                                <span x-text="price ? Number(price).toFixed(2) : '—'"></span> &euro;
                            </p>
                        </div>
                    </div>

                    {{-- Contact form --}}
                    <div x-show="step >= 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="card p-6">
                        <h3 class="font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-accordeur-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Vos coordonnées
                        </h3>

                        <form method="POST" action="{{ route('reservation.store') }}" id="reservationForm" class="space-y-4">
                            @csrf
                            <input type="hidden" name="room_id" value="{{ $room->id }}">
                            <input type="hidden" name="date" :value="selectedDate">
                            <input type="hidden" name="time_slot_id" :value="selectedSlotId">
                            <input type="hidden" name="pricing_profile_id" :value="selectedProfileId">

                            <div>
                                <label for="name" class="form-label">Nom complet</label>
                                <input type="text" name="name" id="name" class="form-input" placeholder="Jean Dupont" value="{{ old('name', $user?->name) }}" required>
                            </div>

                            <div>
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-input" placeholder="jean@exemple.com" value="{{ old('email', $user?->email) }}" required>
                            </div>

                            <div>
                                <label for="phone" class="form-label">Téléphone</label>
                                <input type="text" name="phone" id="phone" class="form-input" placeholder="0694 00 00 00" value="{{ old('phone') }}" required>
                            </div>

                            {{-- Submit --}}
                            <button type="submit" id="reserveBtn"
                                    :disabled="!canSubmit"
                                    :class="canSubmit ? 'btn-accent' : 'btn bg-gray-300 text-gray-500 cursor-not-allowed'"
                                    class="w-full justify-center py-3.5 text-base mt-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                Réserver & Payer
                            </button>

                            <p id="loading" class="text-center text-sm text-accordeur-600 font-medium hidden">
                                Redirection vers le paiement...
                            </p>
                        </form>
                    </div>

                    {{-- Helper message when not ready --}}
                    <div x-show="step < 3" class="text-center py-4">
                        <p class="text-sm text-gray-400">
                            <template x-if="!selectedDate">
                                <span>Sélectionnez une date dans le calendrier pour commencer</span>
                            </template>
                            <template x-if="selectedDate && !selectedSlotId">
                                <span>Choisissez un créneau horaire disponible</span>
                            </template>
                            <template x-if="selectedDate && selectedSlotId && !selectedProfileId">
                                <span>Sélectionnez votre profil tarifaire</span>
                            </template>
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </section>

</div>

@endsection

@push('scripts')
<script>
function reservationCalendar() {
    const now = new Date();
    const reservationsRaw = @json($reservations);
    const monthNames = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
    const dayNames = ['dimanche','lundi','mardi','mercredi','jeudi','vendredi','samedi'];

    return {
        currentMonth: now.getMonth(),
        currentYear: now.getFullYear(),
        selectedDate: null,
        selectedSlotId: null,
        selectedSlotLabel: '',
        selectedProfileId: '',
        price: null,
        submitting: false,
        reservations: reservationsRaw,

        get monthName() {
            return monthNames[this.currentMonth];
        },

        get step() {
            if (this.selectedProfileId && this.selectedSlotId && this.selectedDate) return 3;
            if (this.selectedSlotId && this.selectedDate) return 2;
            if (this.selectedDate) return 1;
            return 0;
        },

        get canSubmit() {
            return this.selectedDate && this.selectedSlotId && this.selectedProfileId && this.price;
        },

        get formattedSelectedDate() {
            if (!this.selectedDate) return '';
            const d = new Date(this.selectedDate + 'T00:00:00');
            return dayNames[d.getDay()] + ' ' + d.getDate() + ' ' + monthNames[d.getMonth()] + ' ' + d.getFullYear();
        },

        get weeks() {
            const firstDay = new Date(this.currentYear, this.currentMonth, 1);
            const lastDay = new Date(this.currentYear, this.currentMonth + 1, 0);
            const totalDays = lastDay.getDate();

            let startDow = firstDay.getDay();
            if (startDow === 0) startDow = 7;
            startDow -= 1; // Monday = 0

            const today = new Date();
            today.setHours(0,0,0,0);

            const weeks = [];
            let currentWeek = [];

            // Pad start
            for (let i = 0; i < startDow; i++) {
                currentWeek.push({ date: null, dayNum: '', isPast: true, isToday: false, isSelected: false });
            }

            for (let d = 1; d <= totalDays; d++) {
                const date = new Date(this.currentYear, this.currentMonth, d);
                date.setHours(0,0,0,0);
                const dateStr = this.formatDate(date);

                currentWeek.push({
                    date: dateStr,
                    dayNum: d,
                    isPast: date < today,
                    isToday: date.getTime() === today.getTime(),
                    isSelected: this.selectedDate === dateStr,
                });

                if (currentWeek.length === 7) {
                    weeks.push(currentWeek);
                    currentWeek = [];
                }
            }

            // Pad end
            while (currentWeek.length > 0 && currentWeek.length < 7) {
                currentWeek.push({ date: null, dayNum: '', isPast: true, isToday: false, isSelected: false });
            }
            if (currentWeek.length) weeks.push(currentWeek);

            return weeks;
        },

        formatDate(date) {
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        },

        prevMonth() {
            if (this.currentMonth === 0) {
                this.currentMonth = 11;
                this.currentYear--;
            } else {
                this.currentMonth--;
            }
        },

        nextMonth() {
            if (this.currentMonth === 11) {
                this.currentMonth = 0;
                this.currentYear++;
            } else {
                this.currentMonth++;
            }
        },

        goToToday() {
            this.currentMonth = now.getMonth();
            this.currentYear = now.getFullYear();
        },

        selectDay(day) {
            if (!day.date || day.isPast) return;
            this.selectedDate = day.date;
            this.selectedSlotId = null;
            this.selectedSlotLabel = '';
            this.selectedProfileId = '';
            this.price = null;
        },

        getReservationsForDate(dateStr) {
            return this.reservations.filter(r => {
                const rDate = (r.date || '').substring(0, 10);
                return rDate === dateStr;
            });
        },

        getSlotStatus(day) {
            if (!day.date) return [];
            const timeSlots = @json($timeSlots);
            const dayReservations = this.getReservationsForDate(day.date);
            return timeSlots.map(slot => ({
                id: slot.id,
                available: !dayReservations.some(r => r.time_slot_id === slot.id),
            }));
        },

        isSlotBooked(slotId) {
            if (!this.selectedDate) return false;
            return this.getReservationsForDate(this.selectedDate).some(r => r.time_slot_id === slotId);
        },

        selectSlot(slotId, label, start, end) {
            if (this.isSlotBooked(slotId)) return;
            this.selectedSlotId = slotId;
            this.selectedSlotLabel = label;
            this.selectedProfileId = '';
            this.price = null;
        },

        async fetchPrice() {
            if (!this.selectedSlotId || !this.selectedProfileId || !this.selectedDate) {
                this.price = null;
                return;
            }

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('room_id', '{{ $room->id }}');
            formData.append('time_slot_id', this.selectedSlotId);
            formData.append('pricing_profile_id', this.selectedProfileId);
            formData.append('date', this.selectedDate);

            try {
                const res = await fetch('{{ route("reservation.price") }}', { method: 'POST', body: formData });
                const data = await res.json();
                this.price = data.price || null;
            } catch (e) {
                this.price = null;
            }
        },

        init() {
            // Handle form submission via AJAX for Stripe redirect
            this.$nextTick(() => {
                const form = document.getElementById('reservationForm');
                if (!form) return;

                const btn = document.getElementById('reserveBtn');
                const loader = document.getElementById('loading');

                btn.addEventListener('click', async (e) => {
                    e.preventDefault();
                    if (this.submitting || !this.canSubmit) return;
                    this.submitting = true;
                    btn.disabled = true;
                    btn.classList.add('opacity-60');
                    loader.classList.remove('hidden');

                    const formData = new FormData(form);

                    try {
                        const res = await fetch(form.action, {
                            method: 'POST',
                            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: formData,
                        });
                        const data = await res.json();

                        if (data.checkout_url) {
                            window.location.href = data.checkout_url;
                        } else {
                            alert(data.message || 'Erreur lors de la création du paiement');
                            this.submitting = false;
                            btn.disabled = false;
                            btn.classList.remove('opacity-60');
                            loader.classList.add('hidden');
                        }
                    } catch (err) {
                        alert('Erreur réseau');
                        this.submitting = false;
                        btn.disabled = false;
                        btn.classList.remove('opacity-60');
                        loader.classList.add('hidden');
                    }
                });
            });
        },
    };
}
</script>
@endpush

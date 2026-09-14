@extends('layouts.public')

@section('title', 'Planning des réservations — L\'Accordeur')
@section('meta_description', 'Consultez en temps réel les disponibilités de nos salles de réunion et espaces à L\'Accordeur, Cayenne.')

@section('content')

{{-- ============================================================== --}}
{{-- PAGE HEADER                                                      --}}
{{-- ============================================================== --}}
<section class="relative overflow-hidden bg-gradient-to-b from-accordeur-50/80 via-accordeur-50/30 to-white">
    {{-- Decorative blobs --}}
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute -top-24 -right-24 w-[500px] h-[500px] rounded-full bg-accordeur-100/40 blur-3xl"></div>
        <div class="absolute top-1/2 -left-32 w-[400px] h-[400px] rounded-full bg-accordeur-200/20 blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
        <div class="text-center max-w-3xl mx-auto"
             x-data="{ show: false }" x-intersect="show = true"
             :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'"
             style="transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1);">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 backdrop-blur-sm border border-accordeur-100 shadow-sm mb-6">
                <svg class="w-4 h-4 text-accordeur-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-sm font-semibold text-accordeur-700">Disponibilités en direct</span>
            </div>
            <h1 class="text-4xl lg:text-5xl font-display font-extrabold text-gray-900 leading-tight tracking-tight">
                Planning des <span class="text-gradient">réservations</span>
            </h1>
            <p class="mt-4 text-lg text-gray-500 leading-relaxed">
                Consultez les disponibilités de nos salles en temps réel
            </p>
        </div>
    </div>

    {{-- Wave divider --}}
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 56" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
            <path d="M0 24C240 56 480 56 720 40C960 24 1200 0 1440 8V56H0V24Z" fill="white"/>
        </svg>
    </div>
</section>


{{-- ============================================================== --}}
{{-- CALENDAR SECTION                                                 --}}
{{-- ============================================================== --}}
<section class="py-12 lg:py-16 bg-white"
    x-data="planningCalendar()"
    x-cloak
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- =============================== --}}
        {{-- FILTER BAR                       --}}
        {{-- =============================== --}}
        <div class="card p-4 sm:p-6 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                {{-- Room filter pills --}}
                <div class="flex flex-wrap gap-2">
                    <button
                        @click="selectedRoom = null"
                        :class="selectedRoom === null
                            ? 'bg-accordeur-600 text-white shadow-lg shadow-accordeur-500/25'
                            : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                        class="px-4 py-2 rounded-full text-sm font-semibold transition-all duration-200"
                    >
                        Toutes les salles
                    </button>
                    <template x-for="room in rooms" :key="room.id">
                        <button
                            @click="selectedRoom = room.id"
                            :class="selectedRoom === room.id
                                ? 'bg-accordeur-600 text-white shadow-lg shadow-accordeur-500/25'
                                : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-full text-sm font-semibold transition-all duration-200"
                        >
                            <span x-text="room.name"></span>
                            <span class="ml-1 opacity-60" x-text="'(' + room.capacity + ')'"></span>
                        </button>
                    </template>
                </div>

                <div class="flex items-center gap-2">
                    {{-- View toggle --}}
                    <div class="flex bg-gray-100 rounded-xl p-0.5 mr-2">
                        <button @click="viewMode = 'week'" :class="viewMode === 'week' ? 'bg-white shadow text-accordeur-700' : 'text-gray-500 hover:text-gray-700'" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all">Semaine</button>
                        <button @click="viewMode = 'month'" :class="viewMode === 'month' ? 'bg-white shadow text-accordeur-700' : 'text-gray-500 hover:text-gray-700'" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all">Mois</button>
                    </div>

                    {{-- Navigation --}}
                    <button
                        @click="viewMode === 'week' ? prevWeek() : prevMonth()"
                        class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-accordeur-50 hover:text-accordeur-600 flex items-center justify-center transition-all duration-200"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>

                    <div class="px-4 py-2 min-w-[180px] text-center">
                        <span class="text-lg font-display font-bold text-gray-900" x-text="viewMode === 'week' ? weekLabel : monthYearLabel"></span>
                    </div>

                    <button
                        @click="viewMode === 'week' ? nextWeek() : nextMonth()"
                        class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-accordeur-50 hover:text-accordeur-600 flex items-center justify-center transition-all duration-200"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>

                    <button
                        @click="goToToday()"
                        class="ml-2 px-4 py-2 rounded-xl text-sm font-semibold bg-accordeur-50 text-accordeur-700 hover:bg-accordeur-100 transition-all duration-200"
                    >
                        Aujourd'hui
                    </button>
                </div>
            </div>
        </div>


        {{-- =============================== --}}
        {{-- WEEKLY GRID VIEW (Excel-like)    --}}
        {{-- =============================== --}}
        <div x-show="viewMode === 'week'" x-transition class="card overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="w-full text-xs border-collapse">
                    <thead>
                        <tr class="bg-accordeur-600">
                            <th class="px-3 py-3 text-left text-white font-bold text-sm min-w-[140px] sticky left-0 bg-accordeur-600 z-10"></th>
                            <template x-for="d in weekDays" :key="d.key">
                                <th class="px-2 py-3 text-center text-white font-bold text-sm min-w-[140px]" :class="isToday(d.date) ? 'bg-accordeur-700' : ''">
                                    <div x-text="d.dayName"></div>
                                    <div class="text-accordeur-200 font-normal text-xs" x-text="d.dayNum"></div>
                                </th>
                            </template>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Room rows --}}
                        <template x-for="room in displayRooms" :key="'wr'+room.id">
                            <tr class="border-t border-gray-100">
                                <td class="px-3 py-2 font-semibold text-gray-900 bg-gray-50 sticky left-0 z-10 border-r border-gray-200 align-top">
                                    <div x-text="room.name" class="text-sm"></div>
                                </td>
                                <template x-for="d in weekDays" :key="'wr'+room.id+d.key">
                                    <td class="px-1.5 py-1.5 align-top border-r border-gray-100 min-h-[60px]" :class="isToday(d.date) ? 'bg-accordeur-50/30' : ''">
                                        <template x-for="item in getWeekCellItems(room.id, d.key)" :key="item.id">
                                            <div class="mb-1 px-1.5 py-1 rounded text-[10px] leading-tight truncate"
                                                :class="{
                                                    'bg-emerald-50 text-emerald-800 border-l-2 border-emerald-400': item.type === 'confirmed',
                                                    'bg-amber-50 text-amber-800 border-l-2 border-amber-400': item.type === 'pending',
                                                    'bg-purple-50 text-purple-800 border-l-2 border-purple-400': item.type === 'event',
                                                    'bg-gray-50 text-gray-400 border-l-2 border-gray-300 line-through': item.type === 'cancelled',
                                                }"
                                                :title="item.full"
                                                x-text="item.label"
                                            ></div>
                                        </template>
                                    </td>
                                </template>
                            </tr>
                        </template>

                        {{-- Services row --}}
                        <tr class="border-t-2 border-amber-200">
                            <td class="px-3 py-2 font-bold text-amber-700 bg-amber-50 sticky left-0 z-10 border-r border-gray-200 align-top text-sm">SERVICES</td>
                            <template x-for="d in weekDays" :key="'ws'+d.key">
                                <td class="px-1.5 py-1.5 align-top border-r border-gray-100 bg-amber-50/20" :class="isToday(d.date) ? 'bg-amber-50/50' : ''">
                                    <template x-for="ev in getServiceEventsForDayByKey(d.key)" :key="'wse'+ev.id">
                                        <div class="mb-1 px-1.5 py-1 rounded text-[10px] leading-tight bg-purple-50 text-purple-800 border-l-2 border-purple-400 truncate"
                                            :title="ev.title"
                                            x-text="(ev.start_time ? (ev.start_time || '').substring(0,5) + ' : ' : '') + (ev.visibility === 'public' ? ev.title : 'Service')"
                                        ></div>
                                    </template>
                                </td>
                            </template>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- =============================== --}}
        {{-- MONTHLY CALENDAR GRID            --}}
        {{-- =============================== --}}
        <div x-show="viewMode === 'month'" x-transition class="card overflow-hidden">

            {{-- Day headers --}}
            <div class="grid grid-cols-7 bg-accordeur-600">
                <template x-for="day in ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim']" :key="day">
                    <div class="py-3 text-center text-sm font-bold text-white tracking-wide" x-text="day"></div>
                </template>
            </div>

            {{-- Calendar weeks --}}
            <div class="border-t border-gray-100">
                <template x-for="(week, weekIndex) in weeks" :key="weekIndex">
                    <div class="grid grid-cols-7 divide-x divide-gray-100"
                         :class="weekIndex < weeks.length - 1 ? 'border-b border-gray-100' : ''">
                        <template x-for="(day, dayIndex) in week" :key="dayIndex">
                            <div
                                class="min-h-[100px] sm:min-h-[120px] p-1.5 sm:p-2 transition-colors duration-150 cursor-pointer relative"
                                :class="{
                                    'bg-white hover:bg-accordeur-50/50': day && !isPast(day) && !isToday(day),
                                    'bg-gray-50/80': day && isPast(day) && !isToday(day),
                                    'bg-accordeur-50/60': day && isToday(day),
                                    'bg-gray-50/30': !day,
                                }"
                                @click="day && selectDay(day)"
                            >
                                <template x-if="day">
                                    <div>
                                        {{-- Day number --}}
                                        <div class="flex items-center justify-between mb-1">
                                            <span
                                                class="inline-flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full text-sm font-semibold transition-all duration-200"
                                                :class="{
                                                    'bg-accordeur-600 text-white ring-2 ring-accordeur-400/30 shadow-md': isToday(day),
                                                    'text-gray-400': isPast(day) && !isToday(day),
                                                    'text-gray-700': !isPast(day) && !isToday(day),
                                                    'ring-2 ring-rouge-500 bg-rouge-50 text-rouge-700': isSelected(day) && !isToday(day),
                                                    'ring-2 ring-rouge-500 shadow-lg': isSelected(day) && isToday(day),
                                                }"
                                                x-text="day.getDate()"
                                            ></span>
                                            {{-- Status indicator --}}
                                            <span
                                                x-show="getDayStatus(day) !== 'none'"
                                                class="hidden sm:inline-flex w-2.5 h-2.5 rounded-full"
                                                :class="{
                                                    'bg-emerald-400': getDayStatus(day) === 'available',
                                                    'bg-amber-400': getDayStatus(day) === 'partial',
                                                    'bg-rouge-400': getDayStatus(day) === 'full',
                                                }"
                                            ></span>
                                        </div>

                                        {{-- Event dots (mobile) --}}
                                        <div class="flex flex-wrap gap-0.5 sm:hidden mt-1">
                                            <template x-for="ev in getEventsForDay(day)" :key="'ev'+ev.id">
                                                <span class="w-2 h-2 rounded-full" :style="'background-color:' + ev.color"></span>
                                            </template>
                                            <template x-for="res in getReservationsForDay(day).slice(0, 3)" :key="res.id">
                                                <span
                                                    class="w-2 h-2 rounded-full"
                                                    :class="{
                                                        'bg-emerald-400': res.status === 'confirmed',
                                                        'bg-amber-400': res.status === 'pending',
                                                        'bg-gray-300': res.status === 'cancelled',
                                                    }"
                                                ></span>
                                            </template>
                                        </div>

                                        {{-- Event badges (desktop) --}}
                                        <div class="hidden sm:flex flex-col gap-1 mt-1">
                                            <template x-for="ev in getEventsForDay(day)" :key="'ev'+ev.id">
                                                <div class="flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-medium leading-tight truncate" :style="'background-color:' + ev.color + '20; color:' + ev.color">
                                                    <span class="w-1.5 h-1.5 rounded-full shrink-0" :style="'background-color:' + ev.color"></span>
                                                    <span class="truncate" x-text="ev.visibility === 'public' ? ((ev.start_time ? (ev.start_time || '').substring(0,5) + ' ' : '') + ev.title) : (ev.room_id ? getRoomName(ev.room_id) + ' — Reserve' : 'Reserve')"></span>
                                                </div>
                                            </template>
                                            <template x-for="res in getReservationsForDay(day).slice(0, 3)" :key="res.id">
                                                <div
                                                    class="flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-medium leading-tight truncate"
                                                    :class="{
                                                        'bg-emerald-50 text-emerald-700': res.status === 'confirmed',
                                                        'bg-amber-50 text-amber-700': res.status === 'pending',
                                                        'bg-gray-100 text-gray-400 line-through': res.status === 'cancelled',
                                                    }"
                                                >
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full shrink-0"
                                                        :class="{
                                                            'bg-emerald-400': res.status === 'confirmed',
                                                            'bg-amber-400': res.status === 'pending',
                                                            'bg-gray-300': res.status === 'cancelled',
                                                        }"
                                                    ></span>
                                                    <span class="truncate" x-text="getResLabel(res)"></span>
                                                </div>
                                            </template>
                                            <span
                                                x-show="getReservationsForDay(day).length > 3"
                                                class="text-[10px] text-gray-400 font-medium pl-1"
                                                x-text="'+' + (getReservationsForDay(day).length - 3) + ' autres'"
                                            ></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        {{-- Legend --}}
        <div class="flex flex-wrap items-center gap-4 sm:gap-6 mt-4 px-1">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                <span class="text-xs text-gray-500 font-medium">Confirmée</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                <span class="text-xs text-gray-500 font-medium">En attente</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-gray-300"></span>
                <span class="text-xs text-gray-500 font-medium">Annulée</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-purple-500"></span>
                <span class="text-xs text-gray-500 font-medium">Service / Evenement</span>
            </div>
            <div class="hidden sm:flex items-center gap-4 ml-auto">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                    <span class="text-xs text-gray-500">Disponible</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                    <span class="text-xs text-gray-500">Partiellement réservée</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rouge-400"></span>
                    <span class="text-xs text-gray-500">Complète</span>
                </div>
            </div>
        </div>


        {{-- =============================== --}}
        {{-- DAY DETAIL PANEL                 --}}
        {{-- =============================== --}}
        <div
            x-show="selectedDay"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="mt-8"
        >
            <div class="card overflow-hidden">
                {{-- Panel header --}}
                <div class="bg-gradient-to-r from-accordeur-600 to-accordeur-700 px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-display font-bold text-white" x-text="selectedDayLabel"></h2>
                            <p class="text-sm text-accordeur-100" x-text="selectedDaySummary"></p>
                        </div>
                    </div>
                    <button
                        @click="selectedDay = null"
                        class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors"
                        aria-label="Fermer"
                    >
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Panel body --}}
                <div class="p-6">
                    {{-- Services (events without room) --}}
                    <template x-if="selectedDay && getServiceEventsForDay(selectedDay).length > 0">
                        <div class="mb-6">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                </div>
                                <h3 class="font-display font-bold text-gray-900">Services</h3>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                <template x-for="ev in getServiceEventsForDay(selectedDay)" :key="'evs'+ev.id">
                                    <div class="rounded-xl border-2 p-4" :style="'border-color:' + ev.color + '40; background-color:' + ev.color + '08'">
                                        <div class="flex items-start gap-3">
                                            <div class="w-3 h-3 rounded-full mt-1 shrink-0" :style="'background-color:' + ev.color"></div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-semibold text-gray-900 text-sm" x-text="ev.visibility === 'public' ? ev.title : 'Service'"></p>
                                                <p x-show="ev.start_time" class="text-xs text-gray-500 mt-0.5" x-text="(ev.start_time || '').substring(0,5) + (ev.end_time ? ' — ' + (ev.end_time || '').substring(0,5) : '')"></p>
                                                <p x-show="ev.description && ev.visibility === 'public'" class="text-xs text-gray-600 mt-1" x-text="ev.description"></p>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    {{-- Rooms grouped --}}
                    <template x-for="room in displayRooms" :key="room.id">
                        <div class="mb-6 last:mb-0">
                            {{-- Room header --}}
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-8 h-8 rounded-lg bg-accordeur-50 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-accordeur-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <h3 class="font-display font-bold text-gray-900" x-text="room.name"></h3>
                                <span class="badge-info text-xs" x-text="room.capacity + ' pers.'"></span>
                            </div>

                            {{-- Slots grid --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                <template x-for="slot in getSlotsForRoom(room.id)" :key="slot.code">
                                    <div
                                        class="rounded-xl border p-4 transition-all duration-200"
                                        :class="{
                                            'border-emerald-200 bg-emerald-50/50': slot.status === 'available',
                                            'border-amber-200 bg-amber-50/50': slot.status === 'pending',
                                            'border-rouge-200 bg-rouge-50/50': slot.status === 'confirmed',
                                            'border-gray-200 bg-gray-50/50': slot.status === 'cancelled',
                                        }"
                                    >
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-semibold text-gray-900" x-text="slot.label"></span>
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wide"
                                                :class="{
                                                    'bg-emerald-100 text-emerald-700': slot.status === 'available',
                                                    'bg-amber-100 text-amber-700': slot.status === 'pending',
                                                    'bg-rouge-100 text-rouge-700': slot.status === 'confirmed',
                                                    'bg-gray-200 text-gray-500': slot.status === 'cancelled',
                                                }"
                                            >
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full"
                                                    :class="{
                                                        'bg-emerald-500': slot.status === 'available',
                                                        'bg-amber-500': slot.status === 'pending',
                                                        'bg-rouge-500': slot.status === 'confirmed',
                                                        'bg-gray-400': slot.status === 'cancelled',
                                                    }"
                                                ></span>
                                                <span x-text="slot.statusLabel"></span>
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-500" x-text="slot.time"></div>

                                        {{-- CTA for available slots --}}
                                        <template x-if="slot.status === 'available'">
                                            <a
                                                :href="'/reservation?room=' + room.id + '&date=' + formatDateParam(selectedDay) + '&slot=' + slot.code"
                                                class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-accordeur-600 text-white text-xs font-semibold hover:bg-accordeur-700 shadow-sm hover:shadow transition-all duration-200"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                                Réserver ce créneau
                                            </a>
                                        </template>

                                        {{-- Info for booked slots --}}
                                        <template x-if="(slot.status === 'confirmed' || slot.status === 'pending') && slot.eventInfo">
                                            <div class="mt-2">
                                                <div class="text-xs font-semibold" :style="'color:' + slot.eventInfo.color">
                                                    <span x-show="slot.eventInfo.start_time" x-text="(slot.eventInfo.start_time || '').substring(0,5) + (slot.eventInfo.end_time ? '-' + (slot.eventInfo.end_time || '').substring(0,5) : '') + ' : '"></span>
                                                    <span x-text="slot.eventInfo.title"></span>
                                                </div>
                                                <div x-show="slot.eventInfo.description" class="text-xs text-gray-500 mt-0.5" x-text="slot.eventInfo.description"></div>
                                            </div>
                                        </template>
                                        <template x-if="(slot.status === 'confirmed' || slot.status === 'pending') && !slot.eventInfo">
                                            <div class="mt-2 text-xs text-gray-400">
                                                Créneau réservé
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    {{-- Empty state --}}
                    <div x-show="displayRooms.length === 0" class="text-center py-12">
                        <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium">Aucune salle disponible pour cette sélection</p>
                    </div>

                    {{-- Global CTA --}}
                    <div class="mt-6 pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <p class="text-sm text-gray-500">
                            Vous souhaitez réserver une salle pour cette date ?
                        </p>
                        <a
                            :href="'/reservation?date=' + formatDateParam(selectedDay)"
                            class="btn-accent px-6 py-2.5 text-sm rounded-xl shadow-lg shadow-rouge-500/20"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Réserver une salle
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
function planningCalendar() {
    return {
        // Data from PHP
        reservations: @json($reservations),
        rooms: @json($rooms),
        planningEvents: @json($planningEvents ?? []),

        // State
        viewMode: 'week',
        currentDate: new Date(),
        weekStart: (() => { const n = new Date(); const d = n.getDay(); n.setDate(n.getDate() - (d === 0 ? 6 : d - 1)); n.setHours(0,0,0,0); return n; })(),
        selectedDay: null,
        selectedRoom: null,

        // Time slot definitions
        timeSlots: [
            { code: 'AM', label: 'Demi-journée matin', time: '07h00 — 13h00', order: 1 },
            { code: 'PM', label: 'Demi-journée après-midi', time: '13h00 — 18h00', order: 2 },
            { code: 'FULL_DAY', label: 'Journée complète', time: '07h00 — 18h00', order: 3 },
        ],

        // French month names
        monthNames: [
            'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
        ],

        frenchDayNames: [
            'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'
        ],

        shortDayNames: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],

        // ---- Computed ----

        get weekLabel() {
            if (!this.weekStart) return '';
            const end = new Date(this.weekStart);
            end.setDate(end.getDate() + 6);
            const s = this.weekStart;
            if (s.getMonth() === end.getMonth()) {
                return s.getDate() + ' - ' + end.getDate() + ' ' + this.monthNames[s.getMonth()] + ' ' + s.getFullYear();
            }
            return s.getDate() + ' ' + this.monthNames[s.getMonth()] + ' - ' + end.getDate() + ' ' + this.monthNames[end.getMonth()] + ' ' + end.getFullYear();
        },

        get weekDays() {
            if (!this.weekStart) return [];
            const days = [];
            for (let i = 0; i < 7; i++) {
                const d = new Date(this.weekStart);
                d.setDate(d.getDate() + i);
                days.push({
                    date: d,
                    key: this.formatDateKey(d),
                    dayName: this.shortDayNames[d.getDay()],
                    dayNum: d.getDate() + '/' + (d.getMonth() + 1),
                });
            }
            return days;
        },

        get monthYearLabel() {
            return this.monthNames[this.currentDate.getMonth()] + ' ' + this.currentDate.getFullYear();
        },

        get weeks() {
            const year = this.currentDate.getFullYear();
            const month = this.currentDate.getMonth();
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();

            // Monday = 0, Sunday = 6
            let startDow = firstDay.getDay() - 1;
            if (startDow < 0) startDow = 6;

            const weeks = [];
            let currentWeek = [];

            // Fill leading empty cells
            for (let i = 0; i < startDow; i++) {
                currentWeek.push(null);
            }

            // Fill days
            for (let d = 1; d <= daysInMonth; d++) {
                currentWeek.push(new Date(year, month, d));
                if (currentWeek.length === 7) {
                    weeks.push(currentWeek);
                    currentWeek = [];
                }
            }

            // Fill trailing empty cells
            if (currentWeek.length > 0) {
                while (currentWeek.length < 7) {
                    currentWeek.push(null);
                }
                weeks.push(currentWeek);
            }

            return weeks;
        },

        get selectedDayLabel() {
            if (!this.selectedDay) return '';
            const d = this.selectedDay;
            return this.frenchDayNames[d.getDay()] + ' ' + d.getDate() + ' ' + this.monthNames[d.getMonth()] + ' ' + d.getFullYear();
        },

        get selectedDaySummary() {
            if (!this.selectedDay) return '';
            const res = this.getReservationsForDay(this.selectedDay);
            const active = res.filter(r => r.status !== 'cancelled');
            if (active.length === 0) return 'Toutes les salles sont disponibles';
            return active.length + ' réservation' + (active.length > 1 ? 's' : '') + ' ce jour';
        },

        get displayRooms() {
            if (this.selectedRoom) {
                return this.rooms.filter(r => r.id === this.selectedRoom);
            }
            return this.rooms;
        },

        // ---- Methods ----

        initWeek() {
            const now = new Date();
            const dow = now.getDay();
            const monday = new Date(now);
            monday.setDate(now.getDate() - (dow === 0 ? 6 : dow - 1));
            monday.setHours(0, 0, 0, 0);
            this.weekStart = monday;
        },

        prevWeek() {
            const d = new Date(this.weekStart);
            d.setDate(d.getDate() - 7);
            this.weekStart = d;
        },

        nextWeek() {
            const d = new Date(this.weekStart);
            d.setDate(d.getDate() + 7);
            this.weekStart = d;
        },

        getWeekCellItems(roomId, dateKey) {
            const items = [];

            // Reservations
            const res = this.reservations.filter(r => {
                const rDate = r.date ? r.date.substring(0, 10) : '';
                return rDate === dateKey && r.room_id === roomId;
            });
            res.forEach(r => {
                const time = this.getResTimeLabel(r);
                let label = time ? time + ' : ' : '';
                let full = label;
                if (r.event_name && r.event_visibility === 'public') {
                    label += r.event_name;
                    full += r.event_name;
                } else if (r.event_name) {
                    label += this.getRoomName(r.room_id) + ' — Reserve';
                    full += this.getRoomName(r.room_id) + ' — Reserve';
                } else {
                    label += r.name;
                    full += r.name;
                }
                let type = r.status === 'paid' ? 'confirmed' : (r.status === 'pending' ? 'pending' : (r.status === 'cancelled' ? 'cancelled' : 'pending'));
                items.push({ id: 'r' + r.id, label, full, type });
            });

            // Planning events for this room
            const events = this.planningEvents.filter(e => {
                const eDate = e.date ? e.date.substring(0, 10) : '';
                return eDate === dateKey && e.room_id === roomId;
            });
            events.forEach(e => {
                const time = e.start_time ? (e.start_time || '').substring(0, 5) + (e.end_time ? '-' + (e.end_time || '').substring(0, 5) : '') : '';
                const label = (time ? time + ' : ' : '') + (e.visibility === 'public' ? e.title : 'Reserve');
                items.push({ id: 'e' + e.id, label, full: label, type: 'event' });
            });

            return items;
        },

        getServiceEventsForDayByKey(dateKey) {
            return this.planningEvents.filter(e => {
                const eDate = e.date ? e.date.substring(0, 10) : '';
                return eDate === dateKey && !e.room_id;
            });
        },

        prevMonth() {
            const d = new Date(this.currentDate);
            d.setMonth(d.getMonth() - 1);
            this.currentDate = d;
            this.selectedDay = null;
        },

        nextMonth() {
            const d = new Date(this.currentDate);
            d.setMonth(d.getMonth() + 1);
            this.currentDate = d;
            this.selectedDay = null;
        },

        goToToday() {
            this.currentDate = new Date();
            this.selectedDay = null;
            this.initWeek();
        },

        selectDay(day) {
            if (this.selectedDay && this.isSameDay(this.selectedDay, day)) {
                this.selectedDay = null;
            } else {
                this.selectedDay = day;
            }
        },

        isToday(day) {
            return this.isSameDay(day, new Date());
        },

        isPast(day) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const d = new Date(day);
            d.setHours(0, 0, 0, 0);
            return d < today;
        },

        isSelected(day) {
            return this.selectedDay && this.isSameDay(this.selectedDay, day);
        },

        isSameDay(a, b) {
            return a.getFullYear() === b.getFullYear()
                && a.getMonth() === b.getMonth()
                && a.getDate() === b.getDate();
        },

        formatDateKey(day) {
            const y = day.getFullYear();
            const m = String(day.getMonth() + 1).padStart(2, '0');
            const d = String(day.getDate()).padStart(2, '0');
            return y + '-' + m + '-' + d;
        },

        formatDateParam(day) {
            if (!day) return '';
            return this.formatDateKey(day);
        },

        getReservationsForDay(day) {
            const key = this.formatDateKey(day);
            let filtered = this.reservations.filter(r => {
                // Handle both "2026-07-03" and "2026-07-03T..." date formats
                const rDate = r.date ? r.date.substring(0, 10) : '';
                return rDate === key;
            });
            if (this.selectedRoom) {
                filtered = filtered.filter(r => r.room_id === this.selectedRoom);
            }
            return filtered;
        },

        getRoomName(roomId) {
            const room = this.rooms.find(r => r.id === roomId);
            return room ? room.name : '';
        },

        getResTimeLabel(res) {
            // Extract HH:MM from start_at datetime string
            const start = res.start_at ? res.start_at.substring(11, 16) : '';
            const end = res.end_at ? res.end_at.substring(11, 16) : '';
            if (start && end) return start + '-' + end;
            if (start) return start;
            return '';
        },

        getResLabel(res) {
            const time = this.getResTimeLabel(res);
            const timePrefix = time ? time + ' : ' : '';
            if (res.event_name && res.event_visibility === 'public') {
                return timePrefix + res.event_name;
            }
            if (res.event_name) {
                return this.getRoomName(res.room_id) + ' — Réservé';
            }
            return timePrefix + this.getRoomName(res.room_id);
        },

        getEventsForDay(day) {
            const key = this.formatDateKey(day);
            return this.planningEvents.filter(e => {
                const eDate = e.date ? e.date.substring(0, 10) : '';
                return eDate === key;
            });
        },

        getEventsForRoomDay(roomId, dateKey) {
            return this.planningEvents.filter(e => {
                const eDate = e.date ? e.date.substring(0, 10) : '';
                return eDate === dateKey && e.room_id === roomId;
            });
        },

        // Time slots with their actual times for overlap checking
        slotTimes: @json($timeSlots->map(fn($s) => ['id' => $s->id, 'start' => $s->start_time, 'end' => $s->end_time])),

        timesOverlap(startA, endA, startB, endB) {
            return startA < endB && endA > startB;
        },

        isSlotBlockedByEvent(roomId, slotOrder, dateKey) {
            const events = this.getEventsForRoomDay(roomId, dateKey);
            const slotDef = this.slotTimes.find(s => s.id === slotOrder);
            if (!slotDef) return false;

            return events.find(e => {
                // No times on event = blocks all day
                if (!e.start_time && !e.end_time) return true;
                // Check time overlap
                const evStart = (e.start_time || '00:00:00').substring(0, 8);
                const evEnd = (e.end_time || '23:59:59').substring(0, 8);
                return this.timesOverlap(evStart, evEnd, slotDef.start, slotDef.end);
            });
        },

        getServiceEventsForDay(day) {
            const key = this.formatDateKey(day);
            return this.planningEvents.filter(e => {
                const eDate = e.date ? e.date.substring(0, 10) : '';
                return eDate === key && !e.room_id;
            });
        },

        getDayStatus(day) {
            const res = this.getReservationsForDay(day);
            const active = res.filter(r => r.status !== 'cancelled');
            if (active.length === 0) return 'none';

            // Calculate how many room-slots are taken
            const roomCount = this.selectedRoom ? 1 : this.rooms.length;
            const totalSlots = roomCount * 2; // AM + PM per room (FULL_DAY counts as both)

            let takenSlots = 0;
            active.forEach(r => {
                const slot = this.getTimeSlotByReservation(r);
                if (slot === 'FULL_DAY') {
                    takenSlots += 2;
                } else {
                    takenSlots += 1;
                }
            });

            if (takenSlots >= totalSlots) return 'full';
            if (takenSlots > 0) return 'partial';
            return 'available';
        },

        getTimeSlotByReservation(reservation) {
            // Try to match by time_slot_id to code
            const slotId = reservation.time_slot_id;
            // Assume time_slot_id maps: 1=AM, 2=PM, 3=FULL_DAY
            if (slotId === 1) return 'AM';
            if (slotId === 2) return 'PM';
            if (slotId === 3) return 'FULL_DAY';
            return 'AM';
        },

        getSlotsForRoom(roomId) {
            if (!this.selectedDay) return [];
            const key = this.formatDateKey(this.selectedDay);
            const dayRes = this.reservations.filter(r => {
                const rDate = r.date ? r.date.substring(0, 10) : '';
                return rDate === key && r.room_id === roomId;
            });

            // Check for FULL_DAY reservation
            const fullDayRes = dayRes.find(r => r.time_slot_id === 3 && r.status !== 'cancelled');

            return this.timeSlots.map(slot => {
                // Find reservation for this slot
                const res = dayRes.find(r => {
                    if (r.status === 'cancelled') return false;
                    return r.time_slot_id === slot.order;
                });

                // If there's a full day booking, AM and PM are also booked
                const blockedByFullDay = fullDayRes && (slot.code === 'AM' || slot.code === 'PM');
                // If AM + PM are both booked, mark FULL_DAY as unavailable too
                const amBooked = dayRes.find(r => r.time_slot_id === 1 && r.status !== 'cancelled');
                const pmBooked = dayRes.find(r => r.time_slot_id === 2 && r.status !== 'cancelled');
                const blockedByParts = slot.code === 'FULL_DAY' && (amBooked || pmBooked);

                let status = 'available';
                let statusLabel = 'Disponible';
                let eventInfo = null;

                // Check if blocked by planning event
                const blockingEvent = this.isSlotBlockedByEvent(roomId, slot.order, key);

                if (res) {
                    status = res.status;
                    const resTime = this.getResTimeLabel(res);
                    if (res.event_name && res.event_visibility === 'public') {
                        statusLabel = (resTime ? resTime + ' : ' : '') + res.event_name;
                    } else if (res.event_name) {
                        statusLabel = 'Réservé';
                    } else {
                        statusLabel = res.status === 'confirmed' ? 'Réservé' : (res.status === 'pending' ? 'En attente' : 'Annulé');
                    }
                } else if (blockedByFullDay) {
                    status = fullDayRes.status;
                    statusLabel = fullDayRes.status === 'confirmed' ? 'Réservé' : 'En attente';
                } else if (blockedByParts) {
                    status = 'confirmed';
                    statusLabel = 'Indisponible';
                } else if (blockingEvent) {
                    status = 'confirmed';
                    if (blockingEvent.visibility === 'public') {
                        statusLabel = blockingEvent.title;
                        eventInfo = blockingEvent;
                    } else {
                        statusLabel = 'Réservé';
                    }
                }

                return {
                    code: slot.code,
                    label: slot.label,
                    time: slot.time,
                    status: status,
                    statusLabel: statusLabel,
                    eventInfo: eventInfo,
                };
            });
        },
    };
}
</script>
@endpush

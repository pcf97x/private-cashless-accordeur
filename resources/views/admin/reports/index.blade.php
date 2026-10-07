@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="page-header flex items-center justify-between">
        <div>
            <h1>Rapports</h1>
            <p>{{ $periodLabel }}</p>
        </div>
        <div class="flex items-center gap-2">
            <form method="POST" action="{{ route('admin.reports.sendEmail') }}">
                @csrf
                <input type="hidden" name="period" value="{{ $period }}">
                <input type="hidden" name="date" value="{{ $date }}">
                <button type="submit" class="btn-outline !py-2 !px-4 !text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Envoyer par email
                </button>
            </form>
            <a href="{{ route('admin.reports.csv', ['period' => $period, 'date' => $date]) }}" class="btn-outline !py-2 !px-4 !text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                CSV
            </a>
            <a href="{{ route('admin.reports.pdf', ['period' => $period, 'date' => $date]) }}" class="btn-primary !py-2 !px-4 !text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                PDF
            </a>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="card p-6 mb-8">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="form-label">Periode</label>
                <select name="period" class="form-input" onchange="this.form.submit()">
                    <option value="day" {{ $period === 'day' ? 'selected' : '' }}>Journalier</option>
                    <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Hebdomadaire</option>
                    <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Mensuel</option>
                    <option value="semester" {{ $period === 'semester' ? 'selected' : '' }}>Semestriel</option>
                </select>
            </div>
            <div>
                <label class="form-label">Date</label>
                <input type="date" name="date" value="{{ $date }}" class="form-input" onchange="this.form.submit()">
            </div>
            <div class="text-sm text-gray-500 pb-2">
                Du <strong>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</strong>
                au <strong>{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</strong>
            </div>
        </form>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-6 gap-4 mb-8">
        <div class="stat-card">
            <div class="stat-icon bg-accordeur-50">
                <svg class="w-5 h-5 text-accordeur-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div class="stat-value">{{ $reservations->count() }}</div>
            <div class="stat-label">Reservations</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-emerald-50">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 1v8m0 0v1"/></svg>
            </div>
            <div class="stat-value">{{ number_format($reservations->where('status', 'paid')->sum(fn($r) => $r->price - $r->discount_amount), 2, ',', ' ') }} &euro;</div>
            <div class="stat-label">CA Salles</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-blue-50">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div class="stat-value">{{ $stats['total_entries'] }}</div>
            <div class="stat-label">Pointages</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-violet-50">
                <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div class="stat-value">{{ $stats['unique_persons'] }}</div>
            <div class="stat-label">Visiteurs uniques</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-amber-50">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="stat-value">{{ $stats['avg_duration'] }}</div>
            <div class="stat-label">Duree moyenne</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-rose-50">
                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div class="stat-value">{{ $stats['total_hours'] }}</div>
            <div class="stat-label">Heures totales</div>
        </div>
    </div>

    {{-- Reservations --}}
    @if($reservations->count())
    <div class="card overflow-hidden mb-8">
        <div class="px-6 py-4 bg-accordeur-50 border-b border-accordeur-100">
            <h3 class="font-display font-bold text-accordeur-800">Reservations ({{ $reservations->count() }})</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50/80">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Horaire</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Salle</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Client</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Evenement</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-500">Prix</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500">Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservations as $r)
                <tr class="border-t border-gray-50 hover:bg-accordeur-50/20">
                    <td class="px-4 py-2.5 font-medium">{{ $r->date->format('d/m') }}</td>
                    <td class="px-4 py-2.5">{{ $r->start_at->format('H:i') }}-{{ $r->end_at->format('H:i') }}</td>
                    <td class="px-4 py-2.5 font-medium text-gray-900">{{ $r->room->name ?? '?' }}</td>
                    <td class="px-4 py-2.5">{{ $r->name }}</td>
                    <td class="px-4 py-2.5 text-gray-500">{{ $r->event_name ?? '—' }}</td>
                    <td class="px-4 py-2.5 text-right font-semibold">{{ number_format($r->price - $r->discount_amount, 2, ',', ' ') }} &euro;</td>
                    <td class="px-4 py-2.5 text-center">
                        @if($r->status === 'paid')
                            <span class="badge badge-success">Payee</span>
                        @elseif($r->status === 'pending')
                            <span class="badge badge-warning">Attente</span>
                        @else
                            <span class="badge">{{ $r->status }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Pointage --}}
    @if($checkins->count())
        <div class="card overflow-hidden">
            <div class="px-6 py-4 bg-blue-50 border-b border-blue-100">
                <h3 class="font-display font-bold text-blue-800">Pointage ({{ $checkins->count() }})</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Visiteur</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Structure</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Motif</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Entree</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Sortie</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Duree</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($checkins as $checkin)
                        @php
                            $duration = '';
                            if ($checkin->entry_at && $checkin->exit_at) {
                                $diff = \Carbon\Carbon::parse($checkin->entry_at)->diff(\Carbon\Carbon::parse($checkin->exit_at));
                                $duration = $diff->format('%Hh%I');
                            }
                        @endphp
                        <tr class="border-t border-gray-50 hover:bg-blue-50/20">
                            <td class="px-4 py-2.5 font-medium whitespace-nowrap">
                                {{ $checkin->scan_date ? \Carbon\Carbon::parse($checkin->scan_date)->format('d/m') : '' }}
                            </td>
                            <td class="px-4 py-2.5">
                                <div class="font-semibold text-gray-900">{{ $checkin->firstname }} {{ $checkin->lastname }}</div>
                            </td>
                            <td class="px-4 py-2.5 text-gray-600">{{ $checkin->company ?? '—' }}</td>
                            <td class="px-4 py-2.5">
                                @if($checkin->purpose)
                                    <span class="badge badge-info text-xs">{{ $checkin->purpose }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-2.5 font-medium">{{ $checkin->entry_at ? \Carbon\Carbon::parse($checkin->entry_at)->format('H:i') : '—' }}</td>
                            <td class="px-4 py-2.5">{{ $checkin->exit_at ? \Carbon\Carbon::parse($checkin->exit_at)->format('H:i') : '—' }}</td>
                            <td class="px-4 py-2.5 font-medium text-accordeur-600">{{ $duration ?: '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="card p-12 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <h3 class="text-lg font-display font-bold text-gray-900 mb-1">Aucun pointage</h3>
            <p class="text-gray-500">Aucune presence enregistree pour cette periode.</p>
        </div>
    @endif

    {{-- Config rapport auto --}}
    <div class="card p-6 mt-8">
        <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 pb-3 mb-4 border-b border-gray-100">Rapport hebdomadaire automatique</h3>
        <form method="POST" action="{{ route('admin.reports.updateSettings') }}" class="space-y-4">
            @csrf
            <div class="flex items-center gap-6">
                <label class="form-label mb-0">Envoi automatique chaque lundi</label>
                <div class="flex gap-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="weekly_report_enabled" value="1" {{ ($reportSettings['weekly_report_enabled'] ?? '0') === '1' ? 'checked' : '' }} class="text-accordeur-500 focus:ring-accordeur-500">
                        <span class="text-sm text-gray-700">Oui</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="weekly_report_enabled" value="0" {{ ($reportSettings['weekly_report_enabled'] ?? '0') !== '1' ? 'checked' : '' }} class="text-accordeur-500 focus:ring-accordeur-500">
                        <span class="text-sm text-gray-700">Non</span>
                    </label>
                </div>
            </div>
            <div>
                <label class="form-label">Destinataires</label>
                <input type="text" name="weekly_report_emails" class="form-input" value="{{ $reportSettings['weekly_report_emails'] ?? '' }}" placeholder="Laisser vide = tous les admins + conciergerie">
                <p class="text-xs text-gray-400 mt-1">Emails separes par une virgule. Si vide, envoye a tous les admins + conciergerie.</p>
            </div>
            <button type="submit" class="btn-primary !py-2 !text-sm">Enregistrer</button>
        </form>
    </div>

</div>
@endsection

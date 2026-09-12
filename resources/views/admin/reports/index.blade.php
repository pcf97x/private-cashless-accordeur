@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="page-header flex items-center justify-between">
        <div>
            <h1>Rapports de presence</h1>
            <p>{{ $periodLabel }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.reports.csv', ['period' => $period, 'date' => $date]) }}" class="btn-outline !py-2 !px-4 !text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export CSV
            </a>
            <a href="{{ route('admin.reports.pdf', ['period' => $period, 'date' => $date]) }}" class="btn-primary !py-2 !px-4 !text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                Export PDF
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
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="stat-card">
            <div class="stat-icon bg-accordeur-50">
                <svg class="w-5 h-5 text-accordeur-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div class="stat-value">{{ $stats['total_entries'] }}</div>
            <div class="stat-label">Pointages</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-emerald-50">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div class="stat-value">{{ $stats['unique_persons'] }}</div>
            <div class="stat-label">Personnes uniques</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-blue-50">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div class="stat-value">{{ $stats['unique_days'] }}</div>
            <div class="stat-label">Jours actifs</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-amber-50">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="stat-value">{{ $stats['avg_duration'] }}</div>
            <div class="stat-label">Duree moyenne</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon bg-violet-50">
                <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div class="stat-value">{{ $stats['total_hours'] }}</div>
            <div class="stat-label">Heures totales</div>
        </div>
    </div>

    {{-- Tableau --}}
    @if($checkins->count())
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Visiteur</th>
                        <th>Societe</th>
                        <th>Motif</th>
                        <th>Entree</th>
                        <th>Sortie</th>
                        <th>Duree</th>
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
                        <tr>
                            <td class="whitespace-nowrap font-medium">
                                {{ $checkin->scan_date ? \Carbon\Carbon::parse($checkin->scan_date)->format('d/m/Y') : '' }}
                            </td>
                            <td>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $checkin->firstname }} {{ $checkin->lastname }}</div>
                                    <div class="text-xs text-gray-500">{{ $checkin->email ?? '' }}</div>
                                </div>
                            </td>
                            <td class="text-sm text-gray-600">{{ $checkin->company ?? '' }}</td>
                            <td>
                                @if($checkin->purpose)
                                    <span class="badge badge-info">{{ $checkin->purpose }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="font-medium">{{ $checkin->entry_at ? \Carbon\Carbon::parse($checkin->entry_at)->format('H:i') : '-' }}</td>
                            <td>{{ $checkin->exit_at ? \Carbon\Carbon::parse($checkin->exit_at)->format('H:i') : '-' }}</td>
                            <td class="font-medium text-accordeur-600">{{ $duration ?: '-' }}</td>
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

</div>
@endsection

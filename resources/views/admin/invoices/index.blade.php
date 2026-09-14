@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="page-header flex items-center justify-between">
        <div>
            <h1>Facturation</h1>
            <p>Gestion des factures</p>
        </div>
        <a href="{{ route('admin.invoices.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Nouvelle facture
        </a>
    </div>

    {{-- Filters --}}
    <div class="card p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="form-label">Recherche</label>
                <input type="text" name="search" class="form-input !py-2 !text-sm" placeholder="Client, N°, programme..." value="{{ request('search') }}">
            </div>
            <div>
                <label class="form-label">Statut</label>
                <select name="status" class="form-input !py-2 !text-sm">
                    <option value="">Tous</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Brouillon</option>
                    <option value="devis_sent" {{ request('status') === 'devis_sent' ? 'selected' : '' }}>Devis envoye</option>
                    <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Envoyee</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Payee</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulee</option>
                </select>
            </div>
            <button type="submit" class="btn-outline !py-2">Filtrer</button>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Client</th>
                    <th>Programme</th>
                    <th class="text-right">Total</th>
                    <th class="text-center">Statut</th>
                    <th>Acompte</th>
                    <th>Facture envoyee</th>
                    <th>Payee le</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                    <tr>
                        <td>
                            <a href="{{ route('admin.invoices.show', $inv) }}" class="text-accordeur-600 font-semibold hover:text-accordeur-800">{{ $inv->invoice_number }}</a>
                        </td>
                        <td class="font-medium text-gray-900">{{ $inv->client_name }}</td>
                        <td class="text-gray-500">{{ $inv->programme ?? '—' }}</td>
                        <td class="text-right font-semibold text-gray-900">{{ number_format($inv->total_ht, 2, ',', ' ') }} &euro;</td>
                        <td class="text-center">
                            <span class="badge" style="background-color: var(--{{ $inv->status_color }}-50, #f9fafb); color: var(--{{ $inv->status_color }}-600, #4b5563); border: 1px solid var(--{{ $inv->status_color }}-200, #e5e7eb);">
                                @php
                                    $badgeClasses = match($inv->status) {
                                        'paid' => 'badge-success',
                                        'cancelled' => 'badge-danger',
                                        'sent' => 'badge-warning',
                                        'devis_sent' => '',
                                        default => '',
                                    };
                                @endphp
                                <span class="{{ $badgeClasses ?: 'badge' }}" @if(!$badgeClasses) style="background-color:#eff6ff;color:#2563eb;border:1px solid #bfdbfe;" @endif>
                                    {{ $inv->status_label }}
                                </span>
                            </span>
                        </td>
                        <td>
                            @if($inv->deposit_amount > 0)
                                {{ number_format($inv->deposit_amount, 2, ',', ' ') }} &euro;
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td>{{ $inv->invoice_sent_at ? $inv->invoice_sent_at->format('d/m/Y') : '—' }}</td>
                        <td>{{ $inv->paid_at ? $inv->paid_at->format('d/m/Y') : '—' }}</td>
                        <td>
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.invoices.show', $inv) }}" class="btn-outline !py-1 !px-2.5 !text-xs !rounded-lg">Voir</a>
                                <a href="{{ route('admin.invoices.pdf', $inv) }}" class="btn-outline !py-1 !px-2.5 !text-xs !rounded-lg">PDF</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-gray-400 py-12">Aucune facture</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

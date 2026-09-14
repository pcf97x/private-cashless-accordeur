@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">

    <a href="{{ route('admin.invoices.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-accordeur-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour aux factures
    </a>

    <div class="page-header flex items-center justify-between">
        <div>
            <h1>Facture {{ $invoice->invoice_number }}</h1>
            <p>{{ $invoice->client_name }} {{ $invoice->programme ? '— ' . $invoice->programme : '' }}</p>
        </div>
        <div class="flex items-center gap-2">
            @php
                $badgeClass = match($invoice->status) {
                    'paid' => 'badge-success',
                    'cancelled' => 'badge-danger',
                    'sent' => 'badge-warning',
                    default => '',
                };
            @endphp
            <span class="{{ $badgeClass ?: 'badge' }} text-sm !px-4 !py-1.5" @if(!$badgeClass) style="background-color:#eff6ff;color:#2563eb;border:1px solid #bfdbfe;" @endif>
                {{ $invoice->status_label }}
            </span>
        </div>
    </div>

    {{-- Info --}}
    <div class="card p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 pb-2 border-b border-gray-100">Client</h3>
                <div><div class="text-xs text-gray-500">Structure</div><div class="font-semibold text-gray-900">{{ $invoice->client_name }}</div></div>
                @if($invoice->client_email)<div><div class="text-xs text-gray-500">Email</div><div class="text-accordeur-600">{{ $invoice->client_email }}</div></div>@endif
                @if($invoice->client_phone)<div><div class="text-xs text-gray-500">Telephone</div><div>{{ $invoice->client_phone }}</div></div>@endif
                @if($invoice->client_address)<div><div class="text-xs text-gray-500">Adresse</div><div class="text-gray-700 whitespace-pre-line">{{ $invoice->client_address }}</div></div>@endif
                @if($invoice->chorus_reference)<div><div class="text-xs text-gray-500">Ref. Chorus</div><div class="font-mono text-sm">{{ $invoice->chorus_reference }}</div></div>@endif
            </div>
            <div class="space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 pb-2 border-b border-gray-100">Facturation</h3>
                @if($invoice->programme)<div><div class="text-xs text-gray-500">Programme</div><div class="font-semibold text-gray-900">{{ $invoice->programme }}</div></div>@endif
                <div><div class="text-xs text-gray-500">Total</div><div class="text-xl font-bold text-accordeur-600">{{ number_format($invoice->total_ht, 2, ',', ' ') }} &euro;</div></div>
                @if($invoice->deposit_amount > 0)
                    <div><div class="text-xs text-gray-500">Acompte recu</div><div class="font-semibold text-emerald-600">{{ number_format($invoice->deposit_amount, 2, ',', ' ') }} &euro; <span class="text-xs text-gray-400">le {{ $invoice->deposit_received_at?->format('d/m/Y') }}</span></div></div>
                    <div><div class="text-xs text-gray-500">Reste a payer</div><div class="font-bold text-amber-600">{{ number_format($invoice->reste_due, 2, ',', ' ') }} &euro;</div></div>
                @endif
                @if($invoice->paid_at)<div><div class="text-xs text-gray-500">Payee le</div><div>{{ $invoice->paid_at->format('d/m/Y') }} — {{ $invoice->payment_method }}</div></div>@endif
                @if($invoice->notes)<div><div class="text-xs text-gray-500">Notes internes</div><div class="text-gray-600 text-sm">{{ $invoice->notes }}</div></div>@endif
            </div>
        </div>
    </div>

    {{-- Lines --}}
    <div class="card overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
            <h3 class="font-display font-bold text-gray-900">Lignes de facturation</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Description</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500">Qte</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-500">P.U.</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-500">Total</th>
                    <th class="px-4 py-3 w-10"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->lines as $line)
                <tr class="border-t border-gray-50">
                    <td class="px-6 py-3 text-gray-900">{{ $line->label }}</td>
                    <td class="px-4 py-3 text-center text-gray-600">{{ $line->quantity == 1 ? '1' : number_format($line->quantity, 0) }}</td>
                    <td class="px-4 py-3 text-right text-gray-600">{{ number_format($line->unit_price, 2, ',', ' ') }} &euro;</td>
                    <td class="px-4 py-3 text-right font-semibold text-gray-900">{{ number_format($line->total, 2, ',', ' ') }} &euro;</td>
                    <td class="px-4 py-3">
                        @if($invoice->status === 'draft')
                        <form method="POST" action="{{ route('admin.invoices.removeLine', $line) }}" onsubmit="return confirm('Supprimer cette ligne ?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="border-t-2 border-gray-200">
                    <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-900">TOTAL</td>
                    <td class="px-4 py-4 text-right text-lg font-bold text-accordeur-600">{{ number_format($invoice->total_ht, 2, ',', ' ') }} &euro;</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        {{-- Add line --}}
        @if($invoice->status === 'draft')
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
            <form method="POST" action="{{ route('admin.invoices.addLine', $invoice) }}" class="flex flex-wrap items-end gap-3">
                @csrf
                <div class="flex-1 min-w-[200px]">
                    <label class="form-label">Description</label>
                    <input type="text" name="label" required class="form-input !py-2 !text-sm" placeholder="Prestation supplementaire...">
                </div>
                <div class="w-20">
                    <label class="form-label">Qte</label>
                    <input type="number" step="1" min="1" name="quantity" value="1" required class="form-input !py-2 !text-sm">
                </div>
                <div class="w-28">
                    <label class="form-label">P.U.</label>
                    <input type="number" step="0.01" min="0" name="unit_price" required class="form-input !py-2 !text-sm" placeholder="0.00">
                </div>
                <button type="submit" class="btn-primary !py-2 !text-sm">Ajouter</button>
            </form>
        </div>
        @endif
    </div>

    {{-- Linked reservations --}}
    @if($invoice->reservations->count())
    <div class="card p-6 mb-6">
        <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 pb-3 mb-3 border-b border-gray-100">Reservations liees</h3>
        <div class="space-y-2">
            @foreach($invoice->reservations as $res)
            <a href="{{ route('admin.reservations.show', $res) }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-accordeur-50/50 transition-colors">
                <div>
                    <span class="font-medium text-gray-900">{{ $res->room->name ?? '?' }}</span>
                    <span class="text-sm text-gray-500 ml-2">{{ $res->date->format('d/m/Y') }} {{ $res->start_at->format('H:i') }}-{{ $res->end_at->format('H:i') }}</span>
                </div>
                <span class="text-sm font-semibold text-accordeur-600">{{ number_format($res->price, 2, ',', ' ') }} &euro;</span>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Workflow actions --}}
    <div class="card p-6">
        <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 pb-3 mb-4 border-b border-gray-100">Actions</h3>
        <div class="flex flex-wrap gap-3">

            <a href="{{ route('admin.invoices.pdf', $invoice) }}" class="btn-outline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Telecharger PDF
            </a>

            <a href="{{ route('admin.invoices.edit', $invoice) }}" class="btn-outline">Modifier</a>

            @if(in_array($invoice->status, ['draft']))
                <form method="POST" action="{{ route('admin.invoices.devisSent', $invoice) }}">@csrf
                    <button type="submit" class="btn bg-blue-50 text-blue-600 hover:bg-blue-100 border border-blue-200">Marquer devis envoye</button>
                </form>
            @endif

            @if(in_array($invoice->status, ['draft', 'devis_sent']))
                <form method="POST" action="{{ route('admin.invoices.deposit', $invoice) }}" class="flex items-center gap-2">@csrf
                    <input type="number" step="0.01" min="0" name="deposit_amount" value="{{ $invoice->deposit_amount }}" class="form-input !py-2 !text-sm !w-28" placeholder="Acompte">
                    <button type="submit" class="btn bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-200 !py-2 !text-sm">Enregistrer acompte</button>
                </form>
            @endif

            @if(in_array($invoice->status, ['draft', 'devis_sent']))
                <form method="POST" action="{{ route('admin.invoices.markSent', $invoice) }}">@csrf
                    <button type="submit" class="btn bg-amber-50 text-amber-600 hover:bg-amber-100 border border-amber-200">Marquer facture envoyee</button>
                </form>
            @endif

            @if(in_array($invoice->status, ['sent', 'devis_sent', 'draft']))
                <form method="POST" action="{{ route('admin.invoices.markPaid', $invoice) }}" class="flex items-center gap-2">@csrf
                    <select name="payment_method" required class="form-input !py-2 !text-sm !w-auto">
                        <option value="virement">Virement</option>
                        <option value="cheque">Cheque</option>
                        <option value="carte">Carte</option>
                        <option value="especes">Especes</option>
                        <option value="chorus">Chorus</option>
                    </select>
                    <button type="submit" class="btn bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-200 !py-2">Marquer payee</button>
                </form>
            @endif

            @if($invoice->status !== 'cancelled')
                <form method="POST" action="{{ route('admin.invoices.cancel', $invoice) }}" onsubmit="return confirm('Annuler cette facture ?');">@csrf
                    <button type="submit" class="btn bg-red-50 text-red-600 hover:bg-red-100 border border-red-200">Annuler</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection

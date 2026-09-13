@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Back link --}}
    <a href="{{ route('admin.reservations.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-accordeur-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour aux réservations
    </a>

    <div class="page-header flex items-center justify-between">
        <div>
            <h1>Réservation #{{ $reservation->id }}</h1>
            <p>Créée le {{ $reservation->created_at->format('d/m/Y à H:i') }}</p>
        </div>
        <div>
            @if($reservation->status === 'paid')
                <span class="badge badge-success text-sm !px-4 !py-1.5">Payée</span>
            @elseif($reservation->status === 'cancelled')
                <span class="badge badge-danger text-sm !px-4 !py-1.5">Annulée</span>
            @elseif($reservation->status === 'devis')
                <span class="badge text-sm !px-4 !py-1.5" style="background-color: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;">Devis</span>
            @else
                <span class="badge badge-warning text-sm !px-4 !py-1.5">En attente</span>
            @endif
        </div>
    </div>

    {{-- Details card --}}
    <div class="card p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 pb-2 border-b border-gray-100">Réservation</h3>
                <div>
                    <div class="text-xs text-gray-500">Salle</div>
                    <div class="font-semibold text-gray-900">{{ $reservation->room->name ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500">Date</div>
                    <div class="font-semibold text-gray-900">{{ $reservation->date->format('d/m/Y') }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500">Créneau</div>
                    <div class="font-semibold text-gray-900">{{ $reservation->start_at->format('H\hi') }} &rarr; {{ $reservation->end_at->format('H\hi') }}</div>
                    @if($reservation->start_at->format('H:i') !== \Carbon\Carbon::parse($reservation->timeSlot->start_time)->format('H:i') || $reservation->end_at->format('H:i') !== \Carbon\Carbon::parse($reservation->timeSlot->end_time)->format('H:i'))
                        <div class="text-xs text-gray-400">Tarif : {{ $reservation->timeSlot->label }}</div>
                    @endif
                </div>
                <div>
                    <div class="text-xs text-gray-500">Prix</div>
                    @if($reservation->discount_amount > 0)
                        <div class="text-sm text-gray-400 line-through">{{ number_format($reservation->price, 2, ',', ' ') }} &euro;</div>
                        <div class="text-xl font-bold text-accordeur-600">{{ number_format($reservation->price - $reservation->discount_amount, 2, ',', ' ') }} &euro;</div>
                        <div class="text-xs text-emerald-600 font-medium">{{ $reservation->discount_label }} (-{{ number_format($reservation->discount_amount, 2, ',', ' ') }} &euro;)</div>
                    @else
                        <div class="text-xl font-bold text-accordeur-600">{{ number_format($reservation->price, 2, ',', ' ') }} &euro;</div>
                    @endif
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 pb-2 border-b border-gray-100">Client</h3>
                <div>
                    <div class="text-xs text-gray-500">Nom</div>
                    <div class="font-semibold text-gray-900">{{ $reservation->name }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500">Email</div>
                    <div class="text-accordeur-600">{{ $reservation->email }}</div>
                </div>
                <div>
                    <div class="text-xs text-gray-500">Téléphone</div>
                    <div class="text-gray-900">{{ $reservation->phone ?? '—' }}</div>
                </div>
                @if($reservation->event_name)
                <div>
                    <div class="text-xs text-gray-500">Evenement</div>
                    <div class="font-semibold text-gray-900">{{ $reservation->event_name }}</div>
                    <div class="text-xs {{ $reservation->event_visibility === 'public' ? 'text-emerald-600' : 'text-amber-600' }}">
                        {{ $reservation->event_visibility === 'public' ? 'Public (visible dans le planning)' : 'Prive (affiche "Reserve")' }}
                    </div>
                </div>
                @endif
                @if($reservation->stripe_session_id)
                <div>
                    <div class="text-xs text-gray-500">Session Stripe</div>
                    <div class="text-xs text-gray-400 font-mono break-all">{{ $reservation->stripe_session_id }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Devis info --}}
    @if($reservation->devis_token)
    <div class="card p-6 mb-6">
        <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 pb-3 mb-4 border-b border-gray-100">Devis</h3>
        <div class="space-y-3">
            @if($reservation->devis_notes)
            <div>
                <div class="text-xs text-gray-500">Details du devis</div>
                <div class="text-gray-900 whitespace-pre-line">{{ $reservation->devis_notes }}</div>
            </div>
            @endif
            <div>
                <div class="text-xs text-gray-500">Lien client</div>
                <div class="flex items-center gap-2">
                    <code class="text-xs bg-gray-100 rounded px-2 py-1 break-all">{{ url('/devis/' . $reservation->devis_token . '/accept') }}</code>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Actions --}}
    <div class="card p-6">
        <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 pb-3 mb-4 border-b border-gray-100">Actions</h3>
        <div class="flex flex-wrap gap-3">
            <form method="POST" action="{{ route('admin.reservations.resendEmail', $reservation) }}">
                @csrf
                <button type="submit" class="btn-outline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ $reservation->status === 'devis' ? 'Renvoyer le devis' : 'Renvoyer l\'email' }}
                </button>
            </form>

            @if ($reservation->status === 'pending')
                <form method="POST" action="{{ route('admin.reservations.confirmPayment', $reservation) }}" class="flex items-center gap-2">
                    @csrf
                    <select name="payment_method" required class="form-input !py-2 !text-sm !w-auto">
                        <option value="especes">Especes</option>
                        <option value="carte">Carte bancaire</option>
                        <option value="virement">Virement</option>
                        <option value="cheque">Cheque</option>
                        <option value="autre">Autre</option>
                    </select>
                    <button type="submit" class="btn bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Valider le paiement
                    </button>
                </form>
            @endif

            @if (in_array($reservation->status, ['pending', 'paid', 'devis']))
                <form method="POST" action="{{ route('admin.reservations.cancel', $reservation) }}" onsubmit="return confirm('{{ $reservation->status === 'devis' ? 'Annuler ce devis ?' : ($reservation->status === 'pending' ? 'Annuler cette reservation ?' : 'Confirmer l\'annulation et le remboursement Stripe ?') }}');">
                    @csrf
                    <button type="submit" class="btn bg-red-50 text-red-600 hover:bg-red-100 focus:ring-red-500 border border-red-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        {{ $reservation->status === 'devis' ? 'Annuler le devis' : ($reservation->status === 'pending' ? 'Annuler' : 'Annuler & rembourser') }}
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Remise --}}
    @if(in_array($reservation->status, ['paid', 'pending', 'devis']))
    <div class="card p-6 mt-6">
        <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 pb-3 mb-4 border-b border-gray-100">Remise</h3>

        @if($reservation->discount_amount > 0)
            <div class="flex items-center justify-between p-4 rounded-xl border border-emerald-200 bg-emerald-50/30 mb-4">
                <div>
                    <span class="font-semibold text-gray-900">{{ $reservation->discount_label }}</span>
                    <span class="text-emerald-600 font-bold ml-2">-{{ number_format($reservation->discount_amount, 2, ',', ' ') }} &euro;</span>
                </div>
                <form method="POST" action="{{ route('admin.reservations.removeDiscount', $reservation) }}" onsubmit="return confirm('Supprimer la remise ?');">
                    @csrf
                    <button type="submit" class="btn !py-1 !px-2.5 !text-xs !rounded-lg bg-red-50 text-red-600 hover:bg-red-100 border border-red-200">Supprimer</button>
                </form>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.reservations.applyDiscount', $reservation) }}" class="flex flex-wrap items-end gap-3" x-data="{ discountType: 'percent' }">
            @csrf
            <div>
                <label class="form-label">Type</label>
                <select name="discount_type" x-model="discountType" class="form-input !py-2 !text-sm">
                    <option value="percent">Pourcentage (%)</option>
                    <option value="fixed">Montant fixe (EUR)</option>
                </select>
            </div>
            <div>
                <label class="form-label" x-text="discountType === 'percent' ? 'Pourcentage' : 'Montant'"></label>
                <input type="number" step="0.01" min="0" :max="discountType === 'percent' ? 100 : {{ $reservation->price }}" name="discount_value" required class="form-input !py-2 !text-sm !w-28" placeholder="10">
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="form-label">Motif (optionnel)</label>
                <input type="text" name="discount_label" class="form-input !py-2 !text-sm" placeholder="Ex: Fidelite, Partenariat...">
            </div>
            <button type="submit" class="btn bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-200 !py-2">
                Appliquer
            </button>
        </form>
    </div>
    @endif

    {{-- Supplements --}}
    @if(in_array($reservation->status, ['paid', 'pending']))
    <div class="card p-6 mt-6">
        <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 pb-3 mb-4 border-b border-gray-100">Complements / Paiements additionnels</h3>

        {{-- Existing supplements --}}
        @if($reservation->supplements->count())
        <div class="space-y-3 mb-6">
            @foreach($reservation->supplements as $sup)
            <div class="flex items-center justify-between p-4 rounded-xl border {{ $sup->status === 'paid' ? 'border-emerald-200 bg-emerald-50/30' : ($sup->status === 'cancelled' ? 'border-gray-200 bg-gray-50 opacity-60' : 'border-amber-200 bg-amber-50/30') }}">
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-gray-900">{{ $sup->label }}</span>
                        @if($sup->status === 'paid')
                            <span class="badge badge-success !text-[10px]">Paye</span>
                        @elseif($sup->status === 'cancelled')
                            <span class="badge badge-danger !text-[10px]">Annule</span>
                        @else
                            <span class="badge badge-warning !text-[10px]">En attente</span>
                        @endif
                    </div>
                    @if($sup->description)
                        <p class="text-xs text-gray-500 mt-1">{{ $sup->description }}</p>
                    @endif
                    @if($sup->payment_method)
                        <p class="text-xs text-gray-400 mt-1">Regle par : {{ $sup->payment_method }}</p>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    <span class="font-bold text-accordeur-600 whitespace-nowrap">{{ number_format($sup->amount, 2, ',', ' ') }} &euro;</span>

                    @if($sup->status === 'pending')
                    <div class="flex items-center gap-1">
                        @if($sup->token)
                        <form method="POST" action="{{ route('admin.supplements.resend', $sup) }}">
                            @csrf
                            <button type="submit" class="btn !py-1 !px-2 !text-[10px] !rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 border border-blue-200" title="Renvoyer le lien">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('admin.supplements.confirm', $sup) }}" class="flex items-center gap-1">
                            @csrf
                            <select name="payment_method" required class="form-input !py-1 !px-1.5 !text-[10px] !rounded-lg !w-auto">
                                <option value="especes">Especes</option>
                                <option value="carte">Carte</option>
                                <option value="virement">Virement</option>
                                <option value="cheque">Cheque</option>
                            </select>
                            <button type="submit" class="btn !py-1 !px-2 !text-[10px] !rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-200" title="Valider">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.supplements.cancel', $sup) }}" onsubmit="return confirm('Annuler ce complement ?');">
                            @csrf
                            <button type="submit" class="btn !py-1 !px-2 !text-[10px] !rounded-lg bg-red-50 text-red-600 hover:bg-red-100 border border-red-200" title="Annuler">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach

            {{-- Total supplements --}}
            @php $paidTotal = $reservation->supplements->where('status', 'paid')->sum('amount'); @endphp
            @if($paidTotal > 0)
            <div class="flex justify-between items-center pt-2 border-t border-gray-100 text-sm">
                <span class="text-gray-500">Total complements payes</span>
                <span class="font-bold text-accordeur-600">{{ number_format($paidTotal, 2, ',', ' ') }} &euro;</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-500 font-semibold">Total general</span>
                <span class="font-bold text-gray-900 text-lg">{{ number_format($reservation->price + $paidTotal, 2, ',', ' ') }} &euro;</span>
            </div>
            @endif
        </div>
        @endif

        {{-- Add supplement form --}}
        <form method="POST" action="{{ route('admin.reservations.addSupplement', $reservation) }}" class="space-y-4 pt-4 border-t border-gray-100" x-data="{ actionType: 'send_link' }">
            @csrf
            <p class="text-sm font-semibold text-gray-700">Ajouter un complement</p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="sm:col-span-2">
                    <label class="form-label">Libelle</label>
                    <input type="text" name="label" required class="form-input" placeholder="Ex: Sono supplementaire, Repas traiteur...">
                </div>
                <div>
                    <label class="form-label">Montant (EUR)</label>
                    <input type="number" step="0.01" min="0.01" name="amount" required class="form-input" placeholder="50.00">
                </div>
            </div>

            <div>
                <label class="form-label">Details (optionnel)</label>
                <textarea name="description" rows="2" class="form-input" placeholder="Description visible par le client..."></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="form-label">Action</label>
                    <select name="action_type" x-model="actionType" class="form-input">
                        <option value="send_link">Envoyer un lien de paiement par email</option>
                        <option value="manual">Valider manuellement (deja paye)</option>
                    </select>
                </div>
                <div x-show="actionType === 'manual'" x-transition>
                    <label class="form-label">Mode de reglement</label>
                    <select name="payment_method" class="form-input">
                        <option value="especes">Especes</option>
                        <option value="carte">Carte bancaire</option>
                        <option value="virement">Virement</option>
                        <option value="cheque">Cheque</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Ajouter le complement
            </button>
        </form>
    </div>
    @endif

</div>
@endsection

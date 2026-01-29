@extends('layouts.app')

@section('content')
<div style="padding:20px; max-width:900px;">
@if (session('error'))
    <p style="color:red">{{ session('error') }}</p>
@endif
    <h2>Détail réservation #{{ $reservation->id }}</h2>

    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <tr>
            <th>Salle</th>
            <td>{{ $reservation->room->name ?? '—' }}</td>
        </tr>
        <tr>
            <th>Date</th>
            <td>{{ $reservation->date->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th>Créneau</th>
            <td>
                {{ $reservation->timeSlot->start_time }} → {{ $reservation->timeSlot->end_time }}
            </td>
        </tr>
        <tr>
            <th>Client</th>
            <td>{{ $reservation->name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $reservation->email }}</td>
        </tr>
        <tr>
            <th>Téléphone</th>
            <td>{{ $reservation->phone }}</td>
        </tr>
        <tr>
            <th>Prix</th>
            <td>{{ number_format($reservation->price, 2) }} €</td>
        </tr>
        <tr>
            <th>Statut</th>
            <td><strong>{{ strtoupper($reservation->status) }}</strong></td>
        </tr>
        <tr>
            <th>Stripe session</th>
            <td style="font-size:12px;">{{ $reservation->stripe_session_id ?? '—' }}</td>
        </tr>
        <tr>
            <th>Créée le</th>
            <td>{{ $reservation->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        <form method="POST"
      action="{{ route('admin.reservations.resendEmail', $reservation) }}"
      style="margin-bottom: 15px;">
    @csrf
    <button type="submit">
        📩 Renvoyer l’email de confirmation
    </button>
      </form>
    @if ($reservation->status === 'paid')
    <form method="POST"
          action="{{ route('admin.reservations.cancel', $reservation) }}"
          onsubmit="return confirm('Confirmer l’annulation et le remboursement ?');"
          style="margin-top:20px;">
        @csrf
        <button type="submit" style="color:red;">
            ❌ Annuler & rembourser
        </button>
    </form>
@endif

</form>
    </table>

    <br>
@if (session('success'))
    <p style="color: green">{{ session('success') }}</p>
@endif


    <a href="{{ route('admin.reservations.index') }}">← Retour à la liste</a>

</div>
@endsection

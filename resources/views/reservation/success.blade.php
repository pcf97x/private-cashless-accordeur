@extends('layouts.app')

@section('content')
<div style="padding:20px;">
    <h2>Paiement OK ✅</h2>
    <p>Réservation #{{ $reservation->id }}</p>
    <p>Status : <strong>{{ $reservation->status }}</strong></p>
    <a href="{{ route('reservation.index') }}">Retour</a>
</div>
@endsection

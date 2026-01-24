@extends('layouts.app')

@section('content')
<div style="padding:20px;">
    <h2>Paiement annulé ❌</h2>
    <p>Réservation #{{ $reservation->id }}</p>
    <a href="{{ route('reservation.show', ['room' => $reservation->room_id]) }}">Revenir</a>
</div>
@endsection

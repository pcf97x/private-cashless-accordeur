@extends('layouts.app')

@section('content')
<div style="padding:20px; max-width:600px; margin:auto;">

    <h2 style="color:green;">✅ Réservation confirmée</h2>

    <p>Merci <strong>{{ $reservation->name }}</strong>, votre réservation est bien enregistrée.</p>

    <hr>

    <h3>Détails de la réservation</h3>

    <ul>
        <li><strong>Salle :</strong> {{ $reservation->room->name }}</li>
        <li><strong>Date :</strong> {{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}</li>
        <li><strong>Créneau :</strong>
            {{ $reservation->start_at->format('H:i') }}
            →
            {{ $reservation->end_at->format('H:i') }}
        </li>
        <li><strong>Prix :</strong> {{ number_format($reservation->price, 2) }} €</li>
    </ul>

    <hr>

    <p>📩 Un email de confirmation vous a été envoyé.</p>

    <div style="margin-top:20px;">
        <a href="{{ route('reservation.index') }}">← Retour aux salles</a>
    </div>

</div>
@endsection
@php
$start = $reservation->start_at->format('Ymd\THis');
$end   = $reservation->end_at->format('Ymd\THis');

$calendarUrl = 'https://www.google.com/calendar/render?action=TEMPLATE'
    . '&text=' . urlencode('Réservation - ' . $reservation->room->name)
    . '&dates=' . $start . '/' . $end
    . '&details=' . urlencode('Réservation Accordeur')
    . '&location=' . urlencode($reservation->room->name);
@endphp

<p style="margin-top:20px;">
    📅 <a href="{{ $calendarUrl }}" target="_blank">
        Ajouter à Google Calendar
    </a>
</p>

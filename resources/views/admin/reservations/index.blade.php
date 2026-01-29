@extends('layouts.admin')

@section('content')
<h1>Réservations</h1>

<table border="1" cellpadding="8">
    <thead>
        <tr>
            <th>ID</th>
            <th>Salle</th>
            <th>Date</th>
            <th>Créneau</th>
            <th>Client</th>
            <th>Email</th>
            <th>Prix</th>
            <th>Statut</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reservations as $r)
            <tr>
                <td><a href="{{ route('admin.reservations.show', $r) }}">
    {{ $r->id }}
</a></td>
                <td>{{ $r->room->name ?? '-' }}</td>
                <td>{{ $r->date->format('d/m/Y') }}</td>
                <td>
                    {{ $r->start_at->format('H:i') }}
                    →
                    {{ $r->end_at->format('H:i') }}
                </td>
                <td>{{ $r->name }}</td>
                <td>{{ $r->email }}</td>
                <td>{{ number_format($r->price, 2, ',', ' ') }} €</td>
                <td>{{ strtoupper($r->status) }}</td>
            </tr>
        @endforeach
        
    </tbody>
</table>
@endsection

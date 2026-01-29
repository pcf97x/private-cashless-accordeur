<h2>Nouvelle réservation confirmée</h2>

<p><strong>Salle :</strong> {{ $reservation->room->name }}</p>
<p><strong>Date :</strong> {{ $reservation->date }}</p>
<p><strong>Créneau :</strong> {{ $reservation->start_at }} → {{ $reservation->end_at }}</p>

<hr>

<p><strong>Client :</strong></p>
<ul>
    <li>{{ $reservation->name }}</li>
    <li>{{ $reservation->email }}</li>
    <li>{{ $reservation->phone }}</li>
</ul>

<p><strong>Prix :</strong> {{ number_format($reservation->price, 2) }} €</p>
<p>
    👉 <a href="{{ url('/admin/reservations') }}">
        Voir la réservation dans l’admin
    </a>
</p>

<h2>Votre réservation est confirmée ✅</h2>

<p>Bonjour {{ $reservation->name }},</p>

<p>Votre réservation a bien été confirmée.</p>

<ul>
    <li><strong>Salle :</strong> {{ $reservation->room->name ?? '—' }}</li>
    <li><strong>Date :</strong> {{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}</li>
    <li><strong>Heure :</strong> {{ $reservation->start_at }} → {{ $reservation->end_at }}</li>
    <li><strong>Prix :</strong> {{ number_format($reservation->price, 2, ',', ' ') }} €</li>
</ul>

<p>Merci pour votre confiance.</p>

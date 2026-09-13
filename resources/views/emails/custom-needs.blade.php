<h2>Nouvelle demande sur mesure</h2>

<p>Un client a reserve une salle avec des besoins specifiques :</p>

<table cellpadding="8" cellspacing="0" style="border-collapse: collapse; margin: 20px 0; width: 100%;">
    <tr>
        <td style="border-bottom: 1px solid #eee; color: #888; font-size: 13px; width: 120px;">Client</td>
        <td style="border-bottom: 1px solid #eee; font-weight: bold;">{{ $reservation->name }}</td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid #eee; color: #888; font-size: 13px;">Email</td>
        <td style="border-bottom: 1px solid #eee;"><a href="mailto:{{ $reservation->email }}">{{ $reservation->email }}</a></td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid #eee; color: #888; font-size: 13px;">Telephone</td>
        <td style="border-bottom: 1px solid #eee;">{{ $reservation->phone }}</td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid #eee; color: #888; font-size: 13px;">Salle</td>
        <td style="border-bottom: 1px solid #eee; font-weight: bold;">{{ $reservation->room->name ?? '-' }}</td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid #eee; color: #888; font-size: 13px;">Date</td>
        <td style="border-bottom: 1px solid #eee;">{{ $reservation->date->format('d/m/Y') }}</td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid #eee; color: #888; font-size: 13px;">Horaires</td>
        <td style="border-bottom: 1px solid #eee;">{{ $reservation->start_at->format('H\hi') }} - {{ $reservation->end_at->format('H\hi') }}</td>
    </tr>
    @if($reservation->event_name)
    <tr>
        <td style="border-bottom: 1px solid #eee; color: #888; font-size: 13px;">Evenement</td>
        <td style="border-bottom: 1px solid #eee;">{{ $reservation->event_name }}</td>
    </tr>
    @endif
    <tr>
        <td style="border-bottom: 1px solid #eee; color: #888; font-size: 13px;">Prix salle</td>
        <td style="border-bottom: 1px solid #eee; font-weight: bold;">{{ number_format($reservation->price, 2, ',', ' ') }} &euro;</td>
    </tr>
</table>

<div style="background-color: #fef3c7; border: 1px solid #fbbf24; border-radius: 8px; padding: 16px; margin: 20px 0;">
    <p style="font-weight: bold; color: #92400e; margin: 0 0 8px 0;">Besoins sur mesure du client :</p>
    <p style="color: #78350f; margin: 0; white-space: pre-line;">{{ $reservation->custom_needs }}</p>
</div>

<p>Vous pouvez consulter et completer cette reservation depuis le back-office :</p>

<div style="margin: 20px 0;">
    <a href="{{ url('/admin/reservations/' . $reservation->id) }}" style="display: inline-block; padding: 12px 24px; background-color: #0d9488; color: #fff; text-decoration: none; border-radius: 8px; font-weight: bold;">
        Voir la reservation #{{ $reservation->id }}
    </a>
</div>

<p style="font-size: 13px; color: #888;">Pensez a contacter le client pour finaliser les options et envoyer un complement de paiement si necessaire.</p>

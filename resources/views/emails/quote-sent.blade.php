<h2>Votre devis de reservation</h2>

<p>Bonjour {{ $reservation->name }},</p>

<p>Suite a votre demande, voici le devis pour la reservation suivante :</p>

<table cellpadding="8" cellspacing="0" style="border-collapse: collapse; margin: 20px 0;">
    <tr>
        <td style="border-bottom: 1px solid #eee; color: #888; font-size: 13px;">Salle</td>
        <td style="border-bottom: 1px solid #eee; font-weight: bold;">{{ $reservation->room->name ?? '—' }}</td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid #eee; color: #888; font-size: 13px;">Date</td>
        <td style="border-bottom: 1px solid #eee; font-weight: bold;">{{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}</td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid #eee; color: #888; font-size: 13px;">Creneau</td>
        <td style="border-bottom: 1px solid #eee; font-weight: bold;">{{ $reservation->start_at->format('H:i') }} &rarr; {{ $reservation->end_at->format('H:i') }}</td>
    </tr>
    <tr>
        <td style="color: #888; font-size: 13px;">Montant</td>
        <td style="font-size: 20px; font-weight: bold; color: #0d9488;">{{ number_format($reservation->price, 2, ',', ' ') }} &euro;</td>
    </tr>
</table>

@if($reservation->devis_notes)
<p><strong>Details :</strong><br>{{ $reservation->devis_notes }}</p>
@endif

<p>Pour confirmer ce devis et bloquer votre creneau, cliquez sur le bouton ci-dessous :</p>

<div style="margin: 25px 0;">
    <a href="{{ $acceptUrl }}" style="display: inline-block; padding: 12px 28px; background-color: #0d9488; color: #fff; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 15px;">
        Accepter le devis
    </a>
</div>

<p style="font-size: 13px; color: #888;">
    Si vous ne souhaitez pas donner suite, vous pouvez
    <a href="{{ $declineUrl }}" style="color: #888;">refuser le devis</a>.
</p>

<p>Cordialement,<br><strong>L'Accordeur</strong></p>

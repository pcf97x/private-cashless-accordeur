<h2>Paiement complementaire pour votre reservation</h2>

<p>Bonjour {{ $supplement->reservation->name }},</p>

<p>Un complement a ete ajoute a votre reservation :</p>

<table cellpadding="8" cellspacing="0" style="border-collapse: collapse; margin: 20px 0;">
    <tr>
        <td style="border-bottom: 1px solid #eee; color: #888; font-size: 13px;">Reservation</td>
        <td style="border-bottom: 1px solid #eee; font-weight: bold;">{{ $supplement->reservation->room->name }} — {{ $supplement->reservation->date->format('d/m/Y') }}</td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid #eee; color: #888; font-size: 13px;">Complement</td>
        <td style="border-bottom: 1px solid #eee; font-weight: bold;">{{ $supplement->label }}</td>
    </tr>
    @if($supplement->description)
    <tr>
        <td style="border-bottom: 1px solid #eee; color: #888; font-size: 13px;">Details</td>
        <td style="border-bottom: 1px solid #eee;">{{ $supplement->description }}</td>
    </tr>
    @endif
    <tr>
        <td style="color: #888; font-size: 13px;">Montant</td>
        <td style="font-size: 20px; font-weight: bold; color: #0d9488;">{{ number_format($supplement->amount, 2, ',', ' ') }} &euro;</td>
    </tr>
</table>

<p>Pour regler ce complement, cliquez sur le bouton ci-dessous :</p>

<div style="margin: 25px 0;">
    <a href="{{ $payUrl }}" style="display: inline-block; padding: 12px 28px; background-color: #0d9488; color: #fff; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 15px;">
        Payer {{ number_format($supplement->amount, 2, ',', ' ') }} &euro;
    </a>
</div>

<p style="font-size: 13px; color: #888;">Si vous avez deja regle ce montant aupres de l'equipe, ignorez cet email.</p>

<p>Cordialement,<br><strong>L'Accordeur</strong></p>

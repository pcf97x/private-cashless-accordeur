<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre accès — L'Accordeur</title>
</head>
<body style="margin:0; padding:0; background-color:#f8fafb; font-family:'Helvetica Neue', Arial, sans-serif; color:#1e293b;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f8fafb; padding:40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 1px 3px rgba(50,111,124,0.06);">

                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #326F7C, #2b5f6a); padding:32px 40px; text-align:center;">
                            <img src="{{ asset('images/logo-blanc.png') }}" alt="L'Accordeur" height="40" style="height:40px;">
                        </td>
                    </tr>

                    {{-- Content --}}
                    <tr>
                        <td style="padding:40px;">

                            <h1 style="margin:0 0 8px; font-size:24px; font-weight:700; color:#1e293b;">
                                Bienvenue, {{ $checkin->firstname }} !
                            </h1>
                            <p style="margin:0 0 30px; font-size:15px; color:#64748b; line-height:1.6;">
                                Votre accès à L'Accordeur est confirmé. Voici votre billet de visite avec toutes les informations.
                            </p>

                            {{-- Details card --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f8fafb; border-radius:12px; margin-bottom:30px;">
                                <tr>
                                    <td style="padding:24px;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding:6px 0; font-size:13px; color:#64748b; width:130px;">Nom</td>
                                                <td style="padding:6px 0; font-size:14px; font-weight:600; color:#1e293b;">{{ $checkin->lastname }} {{ $checkin->firstname }}</td>
                                            </tr>
                                            @if($checkin->company)
                                            <tr>
                                                <td style="padding:6px 0; font-size:13px; color:#64748b;">Société</td>
                                                <td style="padding:6px 0; font-size:14px; font-weight:600; color:#1e293b;">{{ $checkin->company }}</td>
                                            </tr>
                                            @endif
                                            @if($checkin->email)
                                            <tr>
                                                <td style="padding:6px 0; font-size:13px; color:#64748b;">Email</td>
                                                <td style="padding:6px 0; font-size:14px; color:#326F7C;">{{ $checkin->email }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td style="padding:6px 0; font-size:13px; color:#64748b;">Motif de visite</td>
                                                <td style="padding:6px 0; font-size:14px; font-weight:600; color:#1e293b;">{{ ucfirst($checkin->purpose ?? 'Visiteur') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0; font-size:13px; color:#64748b;">Date</td>
                                                <td style="padding:6px 0; font-size:14px; font-weight:600; color:#1e293b;">{{ $checkin->created_at->format('d/m/Y à H:i') }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- QR Code --}}
                            @if($checkin->weez_ticket_code)
                                <div style="text-align:center; margin-bottom:30px;">
                                    <p style="font-size:14px; font-weight:600; color:#1e293b; margin:0 0 16px;">
                                        Votre billet d'accès
                                    </p>
                                    <div style="display:inline-block; background:#ffffff; border:2px solid #e2e8f0; border-radius:16px; padding:16px;">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $checkin->weez_ticket_code }}"
                                             alt="QR Code"
                                             width="200" height="200"
                                             style="display:block; border-radius:8px;">
                                    </div>
                                    <p style="font-size:11px; color:#94a3b8; margin:12px 0 0; font-family:monospace;">
                                        {{ $checkin->weez_ticket_code }}
                                    </p>
                                </div>
                            @elseif($checkin->qr_token)
                                <div style="text-align:center; margin-bottom:30px;">
                                    <p style="font-size:14px; font-weight:600; color:#1e293b; margin:0 0 16px;">
                                        Votre billet d'accès
                                    </p>
                                    <div style="display:inline-block; background:#ffffff; border:2px solid #e2e8f0; border-radius:16px; padding:16px;">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $checkin->qr_token }}"
                                             alt="QR Code"
                                             width="200" height="200"
                                             style="display:block; border-radius:8px;">
                                    </div>
                                    <p style="font-size:11px; color:#94a3b8; margin:12px 0 0; font-family:monospace;">
                                        {{ $checkin->qr_token }}
                                    </p>
                                </div>
                            @endif

                            {{-- Instructions --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#eef6f7; border-radius:12px; border-left:4px solid #326F7C;">
                                <tr>
                                    <td style="padding:20px 24px;">
                                        <p style="margin:0 0 4px; font-size:14px; font-weight:700; color:#326F7C;">
                                            Comment ça marche ?
                                        </p>
                                        <p style="margin:0; font-size:13px; color:#64748b; line-height:1.6;">
                                            Présentez ce QR code à l'accueil de L'Accordeur lors de votre arrivée.
                                            Il sera scanné pour enregistrer votre entrée.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color:#f8fafb; padding:24px 40px; text-align:center; border-top:1px solid #e2e8f0;">
                            <p style="margin:0 0 4px; font-size:13px; font-weight:600; color:#1e293b;">L'Accordeur</p>
                            <p style="margin:0 0 4px; font-size:12px; color:#94a3b8;">Pôle Associatif de Guyane</p>
                            <p style="margin:0; font-size:12px; color:#94a3b8;">1, rue Roland BARRAT — 97300 Cayenne</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>

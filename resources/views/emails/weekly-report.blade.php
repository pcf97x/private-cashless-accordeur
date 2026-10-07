<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: Arial, sans-serif; font-size: 13px; color: #333; line-height: 1.5; margin: 0; padding: 20px; background: #f5f5f5; }
    .container { max-width: 700px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
    .header { background: linear-gradient(135deg, #0d5c63, #0a4a50); padding: 24px 30px; color: #fff; }
    .header h1 { margin: 0; font-size: 20px; }
    .header p { margin: 4px 0 0; opacity: 0.8; font-size: 13px; }
    .body { padding: 24px 30px; }
    .stats { display: flex; gap: 12px; margin-bottom: 24px; }
    .stat { flex: 1; background: #f8fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; text-align: center; }
    .stat .value { font-size: 24px; font-weight: bold; color: #0d5c63; }
    .stat .label { font-size: 10px; color: #888; text-transform: uppercase; letter-spacing: 0.5px; }
    h2 { font-size: 15px; color: #0d5c63; margin: 24px 0 12px; border-bottom: 2px solid #e5e7eb; padding-bottom: 6px; }
    table { width: 100%; border-collapse: collapse; font-size: 11px; margin-bottom: 20px; }
    th { background: #0d5c63; color: #fff; padding: 8px 6px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
    td { padding: 6px; border-bottom: 1px solid #eee; }
    tr:nth-child(even) td { background: #fafafa; }
    .day-header { background: #f0fdfa; font-weight: bold; color: #0d5c63; }
    .day-header td { padding: 8px 6px; border-bottom: 2px solid #0d5c63; font-size: 12px; }
    .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: bold; }
    .badge-green { background: #d1fae5; color: #065f46; }
    .badge-amber { background: #fef3c7; color: #92400e; }
    .badge-purple { background: #ede9fe; color: #5b21b6; }
    .footer { padding: 16px 30px; background: #f8f9fa; text-align: center; font-size: 11px; color: #999; }
    .no-data { color: #999; font-style: italic; padding: 8px 0; }
</style>
</head>
<body>
<div class="container">

    <div class="header">
        <h1>Rapport hebdomadaire</h1>
        <p>{{ $data['period_label'] }}</p>
    </div>

    <div class="body">

        {{-- Stats --}}
        <table cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 20px;">
            <tr>
                <td width="25%" style="padding: 6px;">
                    <div class="stat">
                        <div class="value">{{ $data['stats']['total_reservations'] }}</div>
                        <div class="label">Reservations</div>
                    </div>
                </td>
                <td width="25%" style="padding: 6px;">
                    <div class="stat">
                        <div class="value">{{ number_format($data['stats']['total_ca'], 2, ',', ' ') }} &euro;</div>
                        <div class="label">CA Salles</div>
                    </div>
                </td>
                <td width="25%" style="padding: 6px;">
                    <div class="stat">
                        <div class="value">{{ $data['stats']['total_checkins'] }}</div>
                        <div class="label">Pointages</div>
                    </div>
                </td>
                <td width="25%" style="padding: 6px;">
                    <div class="stat">
                        <div class="value">{{ $data['stats']['unique_visitors'] }}</div>
                        <div class="label">Visiteurs uniques</div>
                    </div>
                </td>
            </tr>
        </table>

        {{-- Reservations par jour --}}
        <h2>Reservations de la semaine</h2>
        @if(count($data['reservations_by_day']) > 0)
        <table>
            <thead>
                <tr>
                    <th>Horaire</th>
                    <th>Salle</th>
                    <th>Client</th>
                    <th>Evenement</th>
                    <th style="text-align:right">Prix</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['reservations_by_day'] as $day => $reservations)
                    <tr class="day-header">
                        <td colspan="6">{{ $day }}</td>
                    </tr>
                    @foreach($reservations as $r)
                    <tr>
                        <td>{{ $r['time'] }}</td>
                        <td>{{ $r['room'] }}</td>
                        <td>{{ $r['client'] }}</td>
                        <td>{{ $r['event'] ?? '—' }}</td>
                        <td style="text-align:right; font-weight:bold;">{{ $r['price'] }}</td>
                        <td><span class="badge {{ $r['status'] === 'Payee' ? 'badge-green' : 'badge-amber' }}">{{ $r['status'] }}</span></td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
        @else
            <p class="no-data">Aucune reservation cette semaine.</p>
        @endif

        {{-- Pointage par jour --}}
        <h2>Pointage de la semaine</h2>
        @if(count($data['checkins_by_day']) > 0)
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Structure</th>
                    <th>Entree</th>
                    <th>Sortie</th>
                    <th>Duree</th>
                    <th>Motif</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['checkins_by_day'] as $day => $checkins)
                    <tr class="day-header">
                        <td colspan="6">{{ $day }} — {{ count($checkins) }} passage(s)</td>
                    </tr>
                    @foreach($checkins as $c)
                    <tr>
                        <td style="font-weight:bold;">{{ $c['name'] }}</td>
                        <td>{{ $c['company'] ?? '—' }}</td>
                        <td>{{ $c['entry'] }}</td>
                        <td>{{ $c['exit'] }}</td>
                        <td>{{ $c['duration'] }}</td>
                        <td>{{ $c['purpose'] ?? '—' }}</td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
        @else
            <p class="no-data">Aucun pointage cette semaine.</p>
        @endif

    </div>

    <div class="footer">
        L'Accordeur — Rapport genere le {{ now()->format('d/m/Y a H:i') }}
        <br>
        <a href="{{ url('/admin/rapports?period=week&date=' . ($data['week_start'] ?? '')) }}" style="color: #0d5c63; font-weight: bold;">Voir le rapport complet en ligne</a>
        &nbsp;|&nbsp;
        <a href="{{ url('/admin/reservations') }}" style="color: #0d5c63;">Back-office</a>
    </div>

</div>
</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport de presence - {{ $periodLabel }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #0d5c63; padding-bottom: 15px; }
        .header h1 { font-size: 18px; color: #0d5c63; margin: 0 0 5px; }
        .header p { font-size: 12px; color: #666; margin: 0; }
        .header .period { font-size: 14px; font-weight: bold; color: #333; margin-top: 5px; }
        .stats { display: table; width: 100%; margin-bottom: 20px; }
        .stat { display: table-cell; text-align: center; padding: 10px; background: #f0faf8; border: 1px solid #e0f0ee; }
        .stat-num { font-size: 20px; font-weight: bold; color: #0d5c63; }
        .stat-label { font-size: 9px; color: #666; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 3px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #0d5c63; color: white; padding: 8px 6px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 6px; border-bottom: 1px solid #eee; font-size: 10px; }
        tr:nth-child(even) td { background: #fafafa; }
        .footer { text-align: center; margin-top: 20px; font-size: 8px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
        .duration { font-weight: bold; color: #0d5c63; }
    </style>
</head>
<body>

<div class="header">
    <h1>L'Accordeur - Pole Associatif de Guyane</h1>
    <p>Rapport de presence</p>
    <div class="period">{{ $periodLabel }}</div>
    <p>Du {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
</div>

<div class="stats">
    <div class="stat">
        <div class="stat-num">{{ $stats['total_entries'] }}</div>
        <div class="stat-label">Pointages</div>
    </div>
    <div class="stat">
        <div class="stat-num">{{ $stats['unique_persons'] }}</div>
        <div class="stat-label">Personnes uniques</div>
    </div>
    <div class="stat">
        <div class="stat-num">{{ $stats['unique_days'] }}</div>
        <div class="stat-label">Jours actifs</div>
    </div>
    <div class="stat">
        <div class="stat-num">{{ $stats['avg_duration'] }}</div>
        <div class="stat-label">Duree moyenne</div>
    </div>
    <div class="stat">
        <div class="stat-num">{{ $stats['total_hours'] }}</div>
        <div class="stat-label">Heures totales</div>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Nom</th>
            <th>Prenom</th>
            <th>Email</th>
            <th>Societe</th>
            <th>Motif</th>
            <th>Entree</th>
            <th>Sortie</th>
            <th>Duree</th>
        </tr>
    </thead>
    <tbody>
        @foreach($checkins as $checkin)
            @php
                $duration = '';
                if ($checkin->entry_at && $checkin->exit_at) {
                    $diff = \Carbon\Carbon::parse($checkin->entry_at)->diff(\Carbon\Carbon::parse($checkin->exit_at));
                    $duration = $diff->format('%Hh%I');
                }
            @endphp
            <tr>
                <td>{{ $checkin->scan_date ? \Carbon\Carbon::parse($checkin->scan_date)->format('d/m/Y') : '' }}</td>
                <td>{{ $checkin->lastname ?? '' }}</td>
                <td>{{ $checkin->firstname ?? '' }}</td>
                <td>{{ $checkin->email ?? '' }}</td>
                <td>{{ $checkin->company ?? '' }}</td>
                <td>{{ $checkin->purpose ?? '' }}</td>
                <td>{{ $checkin->entry_at ? \Carbon\Carbon::parse($checkin->entry_at)->format('H:i') : '' }}</td>
                <td>{{ $checkin->exit_at ? \Carbon\Carbon::parse($checkin->exit_at)->format('H:i') : '' }}</td>
                <td class="duration">{{ $duration }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    Document genere le {{ now()->format('d/m/Y a H:i') }} - L'Accordeur - https://laccordeur973.fr
</div>

</body>
</html>

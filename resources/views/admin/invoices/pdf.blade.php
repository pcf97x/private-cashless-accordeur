<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; line-height: 1.5; }
        .header { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .company { }
        .company h1 { font-size: 16px; color: #0d5c63; margin-bottom: 4px; }
        .company p { font-size: 10px; color: #666; }
        .invoice-title { text-align: right; }
        .invoice-title h2 { font-size: 22px; color: #0d5c63; margin-bottom: 4px; }
        .invoice-title p { font-size: 10px; color: #888; }
        .client-box { background: #f8f9fa; border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px 16px; margin-bottom: 20px; }
        .client-box h3 { font-size: 9px; text-transform: uppercase; letter-spacing: 1px; color: #999; margin-bottom: 6px; }
        .client-box p { font-size: 11px; }
        .meta-table { width: 100%; margin-bottom: 20px; }
        .meta-table td { padding: 4px 8px; font-size: 10px; }
        .meta-table td:first-child { color: #888; width: 120px; }
        table.lines { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.lines th { background: #0d5c63; color: #fff; padding: 8px 10px; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; text-align: left; }
        table.lines th:nth-child(2), table.lines td:nth-child(2) { text-align: center; }
        table.lines th:nth-child(3), table.lines td:nth-child(3),
        table.lines th:nth-child(4), table.lines td:nth-child(4) { text-align: right; }
        table.lines td { padding: 8px 10px; border-bottom: 1px solid #eee; }
        table.lines tr:nth-child(even) td { background: #fafafa; }
        .totals { text-align: right; margin-bottom: 30px; }
        .totals table { margin-left: auto; }
        .totals td { padding: 4px 12px; }
        .totals .total-label { color: #666; font-size: 11px; }
        .totals .total-value { font-weight: bold; font-size: 11px; }
        .totals .grand-total td { border-top: 2px solid #0d5c63; font-size: 14px; color: #0d5c63; }
        .payment-info { background: #f0fdfa; border: 1px solid #99f6e4; border-radius: 6px; padding: 12px 16px; margin-bottom: 20px; }
        .payment-info h3 { font-size: 9px; text-transform: uppercase; letter-spacing: 1px; color: #0d5c63; margin-bottom: 6px; }
        .payment-info p { font-size: 10px; color: #555; }
        .footer { text-align: center; color: #aaa; font-size: 9px; margin-top: 40px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>

    {{-- Header --}}
    <table width="100%" style="margin-bottom: 30px;">
        <tr>
            <td width="50%" valign="top">
                <div class="company">
                    <h1>{{ $settings['company_name'] }}</h1>
                    @if($settings['company_address'])<p>{!! nl2br(e($settings['company_address'])) !!}</p>@endif
                    @if($settings['company_phone'])<p>Tel : {{ $settings['company_phone'] }}</p>@endif
                    @if($settings['company_email'])<p>{{ $settings['company_email'] }}</p>@endif
                    @if($settings['company_siret'])<p>SIRET : {{ $settings['company_siret'] }}</p>@endif
                </div>
            </td>
            <td width="50%" valign="top" style="text-align: right;">
                <h2 style="font-size: 22px; color: #0d5c63; margin-bottom: 4px;">FACTURE</h2>
                <p style="font-size: 14px; font-weight: bold; color: #333;">N° {{ $invoice->invoice_number }}</p>
                <p style="font-size: 10px; color: #888; margin-top: 4px;">Date : {{ $invoice->created_at->format('d/m/Y') }}</p>
            </td>
        </tr>
    </table>

    {{-- Client --}}
    <div class="client-box">
        <h3>Facture a</h3>
        <p style="font-weight: bold; font-size: 13px;">{{ $invoice->client_name }}</p>
        @if($invoice->client_address)<p>{!! nl2br(e($invoice->client_address)) !!}</p>@endif
        @if($invoice->client_email)<p>{{ $invoice->client_email }}</p>@endif
        @if($invoice->client_phone)<p>Tel : {{ $invoice->client_phone }}</p>@endif
        @if($invoice->chorus_reference)<p style="margin-top: 6px; font-size: 10px; color: #666;">Ref. Chorus : {{ $invoice->chorus_reference }}</p>@endif
    </div>

    {{-- Programme --}}
    @if($invoice->programme)
    <table class="meta-table">
        <tr><td>Programme :</td><td style="font-weight: bold;">{{ $invoice->programme }}</td></tr>
    </table>
    @endif

    {{-- Lines --}}
    <table class="lines">
        <thead>
            <tr>
                <th>Description</th>
                <th>Qte</th>
                <th>Prix unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->lines as $line)
            <tr>
                <td>{{ $line->label }}</td>
                <td>{{ $line->quantity == 1 ? '1' : number_format($line->quantity, 0) }}</td>
                <td>{{ number_format($line->unit_price, 2, ',', ' ') }} &euro;</td>
                <td>{{ number_format($line->total, 2, ',', ' ') }} &euro;</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <div class="totals">
        <table>
            <tr>
                <td class="total-label">Total HT</td>
                <td class="total-value">{{ number_format($invoice->total_ht, 2, ',', ' ') }} &euro;</td>
            </tr>
            <tr>
                <td class="total-label">TVA</td>
                <td class="total-value">Non assujetti</td>
            </tr>
            @if($invoice->deposit_amount > 0)
            <tr>
                <td class="total-label">Acompte recu</td>
                <td class="total-value" style="color: #059669;">- {{ number_format($invoice->deposit_amount, 2, ',', ' ') }} &euro;</td>
            </tr>
            @endif
            <tr class="grand-total">
                <td>{{ $invoice->deposit_amount > 0 ? 'RESTE A PAYER' : 'TOTAL A PAYER' }}</td>
                <td>{{ number_format($invoice->reste_due, 2, ',', ' ') }} &euro;</td>
            </tr>
        </table>
    </div>

    {{-- Payment info --}}
    @if($settings['payment_info'])
    <div class="payment-info">
        <h3>Informations de paiement</h3>
        <p>{!! nl2br(e($settings['payment_info'])) !!}</p>
    </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        {{ $settings['company_name'] }} — Facture generee le {{ now()->format('d/m/Y a H:i') }}
    </div>

</body>
</html>

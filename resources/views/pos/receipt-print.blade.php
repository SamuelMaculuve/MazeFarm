<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('pos.receipt') }} — {{ $sale->sale_number }}</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        /* Screen: show receipt card centred */
        .receipt-wrapper {
            background: white;
            width: 320px;
            padding: 24px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
        }

        /* ── Typography ── */
        .center { text-align: center; }
        .right   { text-align: right; }
        .bold    { font-weight: 700; }
        .small   { font-size: 10px; }
        .large   { font-size: 16px; }
        .muted   { color: #888; }

        /* ── Dividers ── */
        .dashed {
            border: none;
            border-top: 1px dashed #ccc;
            margin: 10px 0;
        }
        .solid {
            border: none;
            border-top: 1px solid #333;
            margin: 10px 0;
        }

        /* ── Header ── */
        .store-name { font-size: 18px; font-weight: 700; letter-spacing: 1px; }
        .store-sub  { font-size: 10px; color: #666; margin-top: 2px; }

        /* ── Items table ── */
        .items { width: 100%; border-collapse: collapse; margin: 8px 0; }
        .items th {
            font-size: 10px;
            color: #888;
            font-weight: 600;
            text-transform: uppercase;
            padding: 4px 0;
            border-bottom: 1px solid #eee;
        }
        .items th:last-child, .items td:last-child { text-align: right; }
        .items th:nth-child(2), .items td:nth-child(2) { text-align: center; }
        .items td {
            padding: 5px 0;
            vertical-align: top;
            font-size: 11px;
            border-bottom: 1px dashed #f0f0f0;
        }
        .items tr:last-child td { border-bottom: none; }
        .item-name { font-weight: 500; color: #222; }
        .item-price { color: #555; }

        /* ── Totals ── */
        .totals { width: 100%; font-size: 12px; }
        .totals td { padding: 3px 0; }
        .totals td:last-child { text-align: right; font-weight: 500; }
        .total-row td { font-size: 14px; font-weight: 700; padding-top: 6px; }

        /* ── Payments ── */
        .payments { width: 100%; font-size: 11px; }
        .payments td { padding: 2px 0; }
        .payments td:last-child { text-align: right; }

        /* ── Status badge ── */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-green { background: #dcfce7; color: #16a34a; }
        .badge-red   { background: #fee2e2; color: #dc2626; }
        .badge-gray  { background: #f3f4f6; color: #6b7280; }

        /* ── No-print controls ── */
        .no-print {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }
        .btn {
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            font-family: inherit;
        }
        .btn-dark  { background: #111827; color: white; }
        .btn-light { background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }
        .btn:hover { opacity: 0.85; }

        /* ── PRINT STYLES ── */
        @media print {
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }

            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .receipt-wrapper {
                box-shadow: none !important;
                border-radius: 0 !important;
                width: 100% !important;
                padding: 8px !important;
            }

            /* Force page size to thermal 80mm */
            @page {
                size: 80mm auto;
                margin: 4mm;
            }

            .no-print { display: none !important; }

            .items td, .items th { font-size: 10px; }
            .totals td { font-size: 11px; }
            .total-row td { font-size: 13px; }
        }
    </style>
</head>
<body>

<div id="receipt">
    <div class="receipt-wrapper">

        {{-- ── Header ── --}}
        <div class="center" style="margin-bottom: 12px;">
            <p class="store-name">{{ strtoupper(config('app.name')) }}</p>
            <p class="store-sub">{{ app()->getLocale() === 'pt' ? 'Farmácia · Moçambique' : 'Pharmacy · Mozambique' }}</p>
            <p class="store-sub" style="margin-top: 4px;">{{ $sale->created_at->format('d/m/Y') }} &nbsp;·&nbsp; {{ $sale->created_at->format('H:i') }}</p>
        </div>

        <hr class="solid">

        {{-- ── Meta ── --}}
        <table style="width:100%; font-size:11px; margin-bottom:4px;">
            <tr>
                <td class="muted">{{ __('pos.sale_number') }}</td>
                <td class="right bold">{{ $sale->sale_number }}</td>
            </tr>
            <tr>
                <td class="muted">{{ __('pos.cashier') }}</td>
                <td class="right">{{ $sale->cashier?->name ?? '—' }}</td>
            </tr>
            @if($sale->customer)
            <tr>
                <td class="muted">{{ __('pos.customer') }}</td>
                <td class="right">{{ $sale->customer->name }}</td>
            </tr>
            @endif
            @if($sale->insuranceCard)
            <tr>
                <td class="muted">{{ __('pos.insurance') }}</td>
                <td class="right">{{ $sale->insuranceCard->insuranceCompany->name }}</td>
            </tr>
            @endif
        </table>

        <hr class="dashed">

        {{-- ── Items ── --}}
        <table class="items">
            <thead>
                <tr>
                    <th style="text-align:left; width:50%">{{ __('pos.product') }}</th>
                    <th>{{ __('pos.qty') }}</th>
                    <th>{{ __('common.unit_price') }}</th>
                    <th>{{ __('common.subtotal') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                <tr>
                    <td class="item-name">{{ $item->product->generic_name }}</td>
                    <td class="center item-price">{{ $item->quantity }}</td>
                    <td class="right item-price">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="right bold">{{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <hr class="dashed">

        {{-- ── Totals ── --}}
        <table class="totals">
            <tr>
                <td class="muted">{{ __('common.subtotal') }}</td>
                <td>{{ number_format($sale->subtotal, 2) }} MT</td>
            </tr>
            @if($sale->discount_amount > 0)
            <tr>
                <td class="muted">{{ __('common.discount') }}</td>
                <td style="color:#dc2626;">−{{ number_format($sale->discount_amount, 2) }} MT</td>
            </tr>
            @endif
            @if($sale->insurance_amount > 0)
            <tr>
                <td style="color:#2563eb;">{{ __('pos.insurance_coverage') }}</td>
                <td style="color:#2563eb;">−{{ number_format($sale->insurance_amount, 2) }} MT</td>
            </tr>
            @endif
            <tr class="total-row">
                <td>{{ strtoupper(__('common.total')) }}</td>
                <td>{{ number_format($sale->total_amount, 2) }} MT</td>
            </tr>
        </table>

        <hr class="dashed">

        {{-- ── Payments ── --}}
        <p class="small muted bold" style="margin-bottom: 4px; text-transform: uppercase;">
            {{ __('pos.payments') }}
        </p>
        <table class="payments">
            @foreach($sale->payments as $payment)
            <tr>
                <td>{{ \App\Models\SalePayment::METHODS[$payment->payment_method] ?? $payment->payment_method }}</td>
                <td class="bold">{{ number_format($payment->amount, 2) }} MT</td>
            </tr>
            @endforeach
        </table>

        <hr class="solid">

        {{-- ── Status ── --}}
        <div class="center" style="margin: 8px 0;">
            @php $st = $sale->status; @endphp
            <span class="badge {{ $st === 'completed' ? 'badge-green' : ($st === 'cancelled' ? 'badge-red' : 'badge-gray') }}">
                {{ \App\Models\Sale::STATUSES[$st] ?? $st }}
            </span>
        </div>

        {{-- ── Footer ── --}}
        <div class="center small muted" style="margin-top: 12px; line-height: 1.6;">
            <p>{{ app()->getLocale() === 'pt' ? 'Obrigado pela sua visita!' : 'Thank you for your visit!' }}</p>
            <p>{{ app()->getLocale() === 'pt' ? 'Guarde este recibo.' : 'Please keep this receipt.' }}</p>
        </div>
    </div>

    {{-- ── Screen-only buttons ── --}}
    <div class="no-print">
        <button class="btn btn-dark" onclick="window.print()">
            🖨 {{ app()->getLocale() === 'pt' ? 'Imprimir / Guardar PDF' : 'Print / Save PDF' }}
        </button>
        <button class="btn btn-light" onclick="window.close()">
            {{ app()->getLocale() === 'pt' ? 'Fechar' : 'Close' }}
        </button>
    </div>
</div>

<script>
    // Auto-print when page loads — remove this line if you prefer manual print
    // window.addEventListener('load', () => window.print());
</script>

</body>
</html>

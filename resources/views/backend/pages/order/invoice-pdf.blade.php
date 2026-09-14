<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice - {{ str_replace(['/', '\\'], '', $order->invoice_no) }}</title>

    <style>
        @page {
            size: A4 portrait;
            /* Keep a small safe gap on every printed/PDF page, including page 2+. */
            margin: 5.5mm 7mm 6mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        html,
        body {
            background: #ffffff;
            font-family: Arial, Helvetica, sans-serif;
            color: #111111;
            font-size: 9px;
            line-height: 1.22;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 5.5mm 7mm 6mm;
            background: #ffffff;
        }

        /* ===== Extra Compact Top Header ===== */
        .top-header {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            border-bottom: 0;
            margin-bottom: 1px;
        }

        .top-header td {
            vertical-align: top;
            padding: 0 0 2px 0;
        }

        .brand-cell {
            width: 68%;
            text-align: left;
        }

        .invoice-cell {
            width: 32%;
            text-align: right;
        }

        .brand-table {
            border-collapse: collapse;
        }

        .brand-table td {
            vertical-align: middle;
            padding: 0;
        }

        .logo-cell {
            width: 31px;
        }

        .logo-cell img {
            max-width: 45px;
            max-height: 45px;
            display: block;
        }

        .logo-fallback {
            width: 31px;
            height: 27px;
            border: 1px solid #cccccc;
            text-align: center;
            line-height: 25px;
            color: #999999;
            font-size: 7px;
        }

        .brand-text-cell {
            padding-left: 7px !important;
        }

        .company-name {
            font-size: 14px;
            font-weight: bold;
            color: #222222;
            line-height: 1.05;
        }

        .company-sub,
        .company-phone {
            font-size: 9px;
            color: #333333;
            line-height: 1.15;
        }

        .invoice-title {
            font-size: 10px;
            line-height: 1.05;
            font-weight: bold;
            color: #111111;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .invoice-date {
            margin-top: 2px;
            font-size: 8.4px;
            color: #222222;
            line-height: 1.2;
        }

        .invoice-id-row {
            width: 100%;
            border-collapse: collapse;
            margin: 1px 0 2px;
            font-size: 8.2px;
        }

        .invoice-id-row td {
            padding: 0;
            vertical-align: middle;
        }

        .invoice-id-left {
            text-align: left;
            width: 62%;
        }

        .invoice-id-right {
            text-align: right;
            width: 38%;
        }

        .strong {
            font-weight: bold;
        }

        /* ===== Item Table ===== */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 8.4px;
        }

        .items-table thead {
            display: table-header-group;
        }

        .items-table tfoot {
            display: table-row-group;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #333333;
            padding: 2.2px 3px;
            vertical-align: middle;
        }

        .items-table th {
            text-align: center;
            font-weight: bold;
            background: #f2f2f2;
            color: #111111;
            line-height: 1.12;
            font-size: 8.4px;
        }

        .items-table tbody tr,
        .items-table tfoot tr {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .items-table td {
            line-height: 1.18;
        }

        .sl-col {
            width: 5.5%;
            text-align: center;
        }

        .product-col {
            width: 45.5%;
            text-align: left;
        }

        .mrp-col {
            width: 10%;
            text-align: right;
        }

        .qty-col {
            width: 6.5%;
            text-align: center;
        }

        .value-col,
        .disc-col,
        .net-col {
            width: 10.833%;
            text-align: right;
        }

        .product-name {
            font-weight: normal;
            font-size: 9.2px;
            line-height: 1.15;
            word-break: break-word;
        }

        .part-name {
            color: #444444;
            font-size: 7.2px;
            line-height: 1.05;
        }

        .total-row td {
            font-weight: bold;
            background: #f7f7f7;
            font-size: 8.5px;
        }

        .empty-row td {
            text-align: center;
            padding: 8px 4px;
            color: #777777;
            font-style: italic;
        }

        /* ===== Bottom Details + Totals ===== */
        .bottom-wrap {
            width: 100%;
            margin-top: 4px;
            border-collapse: collapse;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .bottom-left {
            width: 59%;
            vertical-align: top;
            padding-right: 5mm;
        }

        .bottom-right {
            width: 41%;
            vertical-align: top;
        }

        .section-title {
            font-size: 9.5px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .delivery-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.2px;
        }

        .delivery-table td {
            padding: 0.5px 0;
            vertical-align: top;
            line-height: 1.22;
        }

        .delivery-label {
            width: 25mm;
            font-weight: bold;
        }

        .amount-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.3px;
        }

        .amount-table td {
            padding: 0.7px 0;
            line-height: 1.25;
            vertical-align: top;
        }

        .amount-label {
            text-align: right;
            padding-right: 6px !important;
            font-weight: bold;
        }

        .amount-value {
            width: 26mm;
            text-align: right;
            font-weight: bold;
        }

        .amount-separator td {
            border-top: 1px solid #333333;
            padding-top: 3px !important;
        }

        .net-payable-row td {
            font-size: 9.3px;
            font-weight: bold;
        }

        .terms {
            margin-top: 2.5mm;
            font-size: 7.8px;
            line-height: 1.25;
            color: #222222;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .terms div {
            margin-bottom: 0.5px;
        }

        .no-print {
            display: block;
        }

        .print-btn-area {
            width: 210mm;
            margin: 0 auto 10px;
            text-align: right;
        }

        .print-btn {
            display: inline-block;
            padding: 8px 16px;
            background: #198754;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 13px;
            cursor: pointer;
            border: none;
        }

        @media screen {
            body {
                background: #eef1f5;
                padding: 20px 0;
            }

            .page {
                box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
            }
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none !important;
            }

            .page {
                /* Print/PDF page margin is handled by @page so continuation pages also get top gap. */
                width: auto;
                min-height: auto;
                padding: 0;
                box-shadow: none;
                margin: 0;
            }

            .top-header {
                page-break-inside: avoid;
                break-inside: avoid;
                page-break-after: avoid;
            }

            .items-table {
                page-break-before: auto;
            }

            .items-table thead {
                display: table-header-group;
            }

            .items-table tbody {
                display: table-row-group;
            }

            .items-table tfoot {
                display: table-row-group;
            }

            .items-table tr {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="no-print print-btn-area">
        <button type="button" class="print-btn" onclick="window.print()">
            Print / Save as PDF
        </button>
    </div>

    @php
        $mrpSubtotal = 0;
        $totalDiscount = 0;
        $totalQty = 0;
    @endphp

    <div class="page">
        <table class="top-header" cellpadding="0" cellspacing="0">
            <tr>
                <td class="brand-cell">
                    <table class="brand-table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="logo-cell">
                                @if (!empty($logoBase64))
                                    <img src="{{ $logoBase64 }}" alt="Logo">
                                @else
                                    <div class="logo-fallback">Logo</div>
                                @endif
                            </td>
                            <td class="brand-text-cell">
                                <div class="company-name">Nimi Enterprise</div>
                                <div class="company-sub">Medicine &amp; Healthcare Supplier</div>
                                <div class="company-phone">Phone: {{ $companyPhone ?? '01806-023460' }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="invoice-cell">
                    <div class="invoice-title">Invoice: {{ $order->invoice_no }}</div>
                    <div class="invoice-date">
                        <span class="strong">Date:</span>
                        {{ !empty($order->date) ? date('d/m/Y h:i A', strtotime($order->date)) : date('d/m/Y h:i A') }}
                    </div>
                </td>
            </tr>
        </table>

        <table class="items-table" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th class="sl-col">Sl.</th>
                    <th class="product-col">Product</th>
                    <th class="mrp-col">MRP</th>
                    <th class="qty-col">Qty</th>
                    <th class="value-col">Total Value</th>
                    <th class="disc-col">Total Disc.</th>
                    <th class="net-col">Net Pay</th>
                </tr>
            </thead>

            <tbody>
                @forelse($order_details as $key => $item)
                    @php
                        $qty = (float) ($item->quantity ?? 0);
                        $gross = (float) ($item->unit_price ?? 0) * $qty;

                        $storedValue = (float) ($item->discount ?? 0);
                        $storedType = $item->discount_type ?? '';
                        $hasStoredDiscount = $storedValue > 0 && in_array($storedType, ['percent', 'amount']);

                        // Legacy rows: unit_price was saved as discounted rate while discount was recorded separately.
                        $isLegacyNet = $hasStoredDiscount && abs($gross - (float) ($item->subtotal ?? 0)) < 0.01;

                        if ($isLegacyNet) {
                            $mrpUnit =
                                $storedType == 'percent'
                                    ? (float) ($item->unit_price ?? 0) / max(1 - $storedValue / 100, 0.0001)
                                    : (float) ($item->unit_price ?? 0) + $storedValue;
                        } else {
                            $mrpUnit = (float) ($item->unit_price ?? 0);
                        }

                        $lineMrpTotal = $mrpUnit * $qty;
                        $lineSubtotal = (float) ($item->subtotal ?? 0);
                        $lineDiscount = max($lineMrpTotal - $lineSubtotal, 0);

                        $mrpSubtotal += $lineMrpTotal;
                        $totalDiscount += $lineDiscount;
                        $totalQty += $qty;
                    @endphp

                    <tr>
                        <td class="sl-col">{{ $key + 1 }}</td>
                        <td class="product-col">
                            @if (!empty($item->product->category->title))
                                <span class="product-category fw-bold">{{ $item->product->category->title }}:</span>
                            @endif
                            <span class="product-name">{{ $item->product->name ?? 'N/A' }}</span>
                            @if ($item->part)
                                <br>
                                <span class="part-name">{{ $item->part->name ?? 'N/A' }}</span>
                            @endif
                        </td>
                        <td class="mrp-col">{{ number_format($mrpUnit, 2) }}</td>
                        <td class="qty-col">{{ number_format($qty, $qty == floor($qty) ? 0 : 2) }}</td>
                        <td class="value-col">{{ number_format($lineMrpTotal, 2) }}</td>
                        <td class="disc-col">{{ number_format($lineDiscount, 2) }}</td>
                        <td class="net-col">{{ number_format($lineSubtotal, 2) }}</td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="7">No items found</td>
                    </tr>
                @endforelse
            </tbody>

            @php
                $netTotal = $mrpSubtotal - $totalDiscount;
                $specialDiscount = $netTotal >= 5000 ? ($netTotal * 1) / 100 : 0;
                $netPayable = $netTotal - $specialDiscount;
            @endphp

            <tfoot>
                <tr class="total-row">
                    <td class="sl-col"></td>
                    <td class="product-col">Total</td>
                    <td class="mrp-col"></td>
                    <td class="qty-col">{{ number_format($totalQty, $totalQty == floor($totalQty) ? 0 : 2) }}</td>
                    <td class="value-col">{{ number_format($mrpSubtotal, 2) }}</td>
                    <td class="disc-col">{{ number_format($totalDiscount, 2) }}</td>
                    <td class="net-col">{{ number_format($netTotal, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <table class="bottom-wrap" cellpadding="0" cellspacing="0">
            <tr>
                <td class="bottom-left">
                    <div class="section-title">Delivery Details</div>
                    <table class="delivery-table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="delivery-label">Customer Name:</td>
                            <td>{{ $billing->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="delivery-label">Phone:</td>
                            <td>{{ $billing->phone ?? 'N/A' }}</td>
                        </tr>
                        @if (!empty($billing->email))
                            <tr>
                                <td class="delivery-label">Email:</td>
                                <td>{{ $billing->email }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="delivery-label">Address:</td>
                            <td>
                                {{ $billing->address ?? 'N/A' }}
                                @if (!empty($billing->city))
                                    , {{ $billing->city }}
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>

                <td class="bottom-right">
                    <table class="amount-table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="amount-label">Total Value :</td>
                            <td class="amount-value">{{ number_format($mrpSubtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="amount-label">Discount :</td>
                            <td class="amount-value">{{ number_format($totalDiscount, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="amount-label">Total :</td>
                            <td class="amount-value">{{ number_format($netTotal, 2) }}</td>
                        </tr>
                        @if ($specialDiscount > 0)
                            <tr>
                                <td class="amount-label">Special Dis.(1%) :</td>
                                <td class="amount-value">{{ number_format($specialDiscount, 2) }}</td>
                            </tr>
                        @endif
                        <tr class="amount-separator net-payable-row">
                            <td class="amount-label">Net Payable :</td>
                            <td class="amount-value">{{ number_format($netPayable, 2) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="terms">
            <div>1. Goods once sold will not be taken back or exchanged.</div>
            <div>2. Please check all medicines/products at the time of delivery.</div>
        </div>
    </div>
</body>

</html>

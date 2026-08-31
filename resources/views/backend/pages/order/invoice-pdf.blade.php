<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice - {{ str_replace(['/', '\\'], '', $order->invoice_no) }}</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 0;
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
            color: #333;
            font-size: 11px;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 9mm 11mm 22mm;
            background: #ffffff;
        }

        /* ===== Header ===== */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            border-bottom: 2px solid #1a1a2e;
            padding-bottom: 4px;
        }

        .header-table td {
            vertical-align: middle;
            padding-bottom: 4px;
        }

        .brand-cell {
            width: 55%;
        }

        .brand-table {
            border-collapse: collapse;
        }

        .brand-table td {
            vertical-align: middle;
            padding: 0;
        }

        .brand-table .logo-box img {
            width: 44px;
            height: auto;
            display: block;
        }

        .brand-table .logo-box .logo-fallback {
            width: 44px;
            height: 32px;
            background: #e9ecef;
            border: 1px dashed #ccc;
            text-align: center;
            line-height: 32px;
            font-size: 8px;
            color: #999;
        }

        .brand-table .name-box {
            padding-left: 9px;
        }

        .company-name {
            font-size: 14px;
            font-weight: bold;
            color: #1a1a2e;
            line-height: 1.25;
        }

        .company-sub {
            font-size: 8.5px;
            color: #777;
            letter-spacing: 0.3px;
        }

        .title-cell {
            width: 45%;
            text-align: right;
        }

        .doc-title {
            font-size: 22px;
            font-weight: bold;
            color: #1a1a2e;
            letter-spacing: 3px;
            line-height: 1.1;
        }

        /* ===== Bill To + Meta ===== */
        .info-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px 0;
            margin: 8px -6px 3px;
            table-layout: fixed;
        }

        .info-box {
            background: #f8f9fa;
            border: 1px solid #e3e6ea;
            border-radius: 4px;
            padding: 6px 10px;
            vertical-align: top;
        }

        .info-box-title {
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #1a1a2e;
            border-bottom: 1px solid #d8dce2;
            padding-bottom: 3px;
            margin-bottom: 4px;
        }

        .info-line {
            font-size: 9.5px;
            color: #444;
            line-height: 1.5;
        }

        .info-label {
            font-weight: bold;
            color: #333;
            display: inline-block;
            width: 64px;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table td {
            font-size: 9.5px;
            padding: 0;
            color: #444;
        }

        .meta-table .k {
            color: #777;
            width: 90px;
        }

        .meta-table .v {
            font-weight: bold;
            color: #1a1a2e;
        }

        .paid {
            color: #198754;
        }

        .pending {
            color: #dc3545;
        }

        /* ===== Items ===== */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 9px;
        }

        .items-table thead th {
            background: #1a1a2e;
            color: #ffffff;
            padding: 4px 5px;
            text-align: center;
            border: 1px solid #1a1a2e;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .items-table thead th.tl {
            text-align: left;
        }

        .items-table thead th.tr {
            text-align: right;
        }

        .items-table tbody td {
            padding: 3px 5px;
            text-align: center;
            border: 1px solid #dfe3e8;
            vertical-align: middle;
            font-size: 9px;
            line-height: 1.3;
        }

        .items-table tbody td.tl {
            text-align: left;
        }

        .items-table tbody td.tr {
            text-align: right;
        }

        .items-table tbody tr.alt {
            background: #f8f9fa;
        }

        .part-name {
            color: #888;
            font-size: 8.5px;
        }

        .empty-row td {
            text-align: center;
            padding: 12px 5px;
            color: #999;
            font-style: italic;
        }

        /* ===== Totals ===== */
        .totals-wrap {
            width: 100%;
            margin-top: 8px;
        }

        .totals-left {
            font-size: 8.5px;
            color: #777;
            vertical-align: top;
            padding-top: 2px;
        }

        .totals-note {
            border-left: 2px solid #d8dce2;
            padding-left: 8px;
            line-height: 1.45;
        }

        .totals-table {
            width: 78mm;
            border-collapse: collapse;
            margin-left: auto;
        }

        .totals-table td {
            padding: 4px 7px;
            font-size: 9.5px;
            border: 1px solid #dfe3e8;
        }

        .totals-table .k {
            color: #555;
        }

        .totals-table .v {
            text-align: right;
            font-weight: bold;
            color: #1a1a2e;
        }

        .grand-row td {
            background: #1a1a2e !important;
            color: #ffffff !important;
            border-color: #1a1a2e !important;
            font-size: 11px !important;
            padding: 6px !important;
        }

        .grand-row .k {
            color: #ffffff !important;
        }

        /* ===== Signature + Footer ===== */
        /* Signature is anchored to the bottom of the LAST page, just above the footer */
        .signature-wrap {
            position: absolute;
            bottom: 22mm;
            left: 11mm;
            width: 188mm;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .signature-table td {
            font-size: 8.5px;
            color: #555;
            text-align: center;
            vertical-align: bottom;
        }

        .sign-line {
            border-top: 1px solid #999;
            width: 55mm;
            margin: 0 auto 3px;
            padding-top: 3px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 210mm;
            background: #1a1a2e;
            color: #ffffff;
            text-align: center;
            padding: 5px 12mm 6px;
        }

        .footer .helpline {
            font-size: 9.5px;
            font-weight: bold;
            letter-spacing: 0.4px;
        }

        .footer .policy {
            font-size: 8.5px;
            color: #c9ccd6;
            margin-top: 1px;
        }

        .footer .thanks {
            font-size: 9px;
            color: #ffffff;
            margin-top: 2px;
        }

        /* ===== Screen / Print helpers ===== */
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
                box-shadow: none;
                margin: 0;
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

    <div class="page">

        {{-- Header --}}
        <table class="header-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="brand-cell">
                    <table class="brand-table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="logo-box">
                                @if (!empty($logoBase64))
                                    <img src="{{ $logoBase64 }}" alt="Logo">
                                @else
                                    <div class="logo-fallback">LOGO</div>
                                @endif
                            </td>
                            <td class="name-box">
                                <div class="company-name">Nimi Enterprise</div>
                                <div class="company-sub">Medicine &amp; Healthcare Supplier</div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="title-cell">
                    <div class="doc-title">INVOICE</div>
                </td>
            </tr>
        </table>

        {{-- Bill To + Invoice Meta --}}
        <table class="info-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="info-box" width="55%">
                    <div class="info-box-title">Bill To</div>
                    <div class="info-line">
                        <span class="info-label">Company:</span> {{ $billing->company ?? 'N/A' }}<br>
                        <span class="info-label">Name:</span> {{ $billing->name ?? 'N/A' }}<br>
                        <span class="info-label">Phone:</span> {{ $billing->phone ?? 'N/A' }}<br>
                        @if (!empty($billing->email))
                            <span class="info-label">Email:</span> {{ $billing->email }}<br>
                        @endif
                        <span class="info-label">Address:</span> {{ $billing->address ?? 'N/A' }}
                        @if (!empty($billing->city))
                            , {{ $billing->city }}
                        @endif
                    </div>
                </td>
                <td class="info-box" width="45%">
                    <div class="info-box-title">Invoice Details</div>
                    <table class="meta-table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="k">Invoice No:</td>
                            <td class="v">{{ $order->invoice_no }}</td>
                        </tr>
                        <tr>
                            <td class="k">Date:</td>
                            <td class="v">{{ date('d M Y', strtotime($order->date)) }}</td>
                        </tr>
                        <tr>
                            <td class="k">Payment:</td>
                            <td class="v {{ $order->payment_status == 1 ? 'paid' : 'pending' }}">
                                {{ $order->payment_status == 1 ? 'Paid' : 'Pending' }}
                            </td>
                        </tr>
                        @if (!empty($order->payment_method))
                            <tr>
                                <td class="k">Method:</td>
                                <td class="v">{{ ucfirst($order->payment_method) }}</td>
                            </tr>
                        @endif
                    </table>
                </td>
            </tr>
        </table>

        {{-- Items --}}
        @php
            $mrpSubtotal = 0;
            $totalDiscount = 0;
        @endphp

        <table class="items-table" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th width="6%">#</th>
                    <th width="34%" class="tl">Product</th>
                    <th width="8%">Qty</th>
                    <th width="13%" class="tr">MRP (Tk)</th>
                    <th width="13%" class="tr">Discount</th>
                    <th width="13%" class="tr">Rate (Tk)</th>
                    <th width="13%" class="tr">Amount (Tk)</th>
                </tr>
            </thead>

            <tbody>
                @forelse($order_details as $key => $item)
                    @php
                        $qty = (float) $item->quantity;
                        $gross = $item->unit_price * $qty;

                        $storedValue = (float) ($item->discount ?? 0);
                        $storedType = $item->discount_type ?? '';
                        $hasStoredDiscount = $storedValue > 0 && in_array($storedType, ['percent', 'amount']);

                        // Legacy rows (orders placed before the fix): unit_price was saved
                        // as the already-discounted rate while the discount was recorded
                        // but never applied to subtotal. Detect and back-compute the MRP.
                        $isLegacyNet = $hasStoredDiscount && abs($gross - $item->subtotal) < 0.01;

                        if ($isLegacyNet) {
                            $mrpUnit = $storedType == 'percent'
                                ? $item->unit_price / max(1 - $storedValue / 100, 0.0001)
                                : $item->unit_price + $storedValue;
                        } else {
                            // unit_price is the MRP, subtotal already has the discount applied
                            $mrpUnit = $item->unit_price;
                        }

                        $lineMrpTotal = $mrpUnit * $qty;
                        $lineDiscount = max($lineMrpTotal - $item->subtotal, 0);
                        $netRate = $qty > 0 ? $item->subtotal / $qty : 0;

                        $mrpSubtotal += $lineMrpTotal;
                        $totalDiscount += $lineDiscount;
                    @endphp

                    <tr @class(['alt' => $key % 2 == 1])>
                        <td>{{ $key + 1 }}</td>

                        <td class="tl">
                            {{ $item->product->name ?? 'N/A' }}
                            @if ($item->part)
                                <br>
                                <span class="part-name">Part: {{ $item->part->name ?? 'N/A' }}</span>
                            @endif
                        </td>

                        <td>{{ $item->quantity }}</td>

                        <td class="tr">{{ number_format($mrpUnit, 2) }}</td>

                        <td class="tr">
                            @if ($lineDiscount > 0)
                                @if ($item->discount_type == 'percent' && $item->discount > 0)
                                    {{ $item->discount }}%
                                    <span class="part-name">(Tk {{ number_format($lineDiscount, 2) }})</span>
                                @else
                                    {{ number_format($lineDiscount, 2) }}
                                @endif
                            @else
                                -
                            @endif
                        </td>

                        <td class="tr">{{ number_format($netRate, 2) }}</td>

                        <td class="tr"><b>{{ number_format($item->subtotal, 2) }}</b></td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="7">No items found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Totals --}}
        @php
            // Net total after item-level discounts (e.g. 7000 - 1200 = 5800)
            $netTotal = $mrpSubtotal - $totalDiscount;

            // Special Discount: 1% of total value when order is 5000 or more
            $specialDiscount = $netTotal >= 5000 ? ($netTotal * 1 / 100) : 0;

            // Final payable after special discount (e.g. 5800 - 58 = 5742)
            $netPayable = $netTotal - $specialDiscount;
        @endphp

        <table class="totals-wrap" cellpadding="0" cellspacing="0">
            <tr>
                <td class="totals-left">
                    <div class="totals-note">
                        <b>Note:</b> Goods once sold will not be taken back or exchanged.<br>
                        Please check all medicines at the time of delivery.
                    </div>
                </td>
                <td>
                    <table class="totals-table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="k">Subtotal (MRP):</td>
                            <td class="v">{{ number_format($mrpSubtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="k">Total Discount:</td>
                            <td class="v">- {{ number_format($totalDiscount, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="k">Total:</td>
                            <td class="v">{{ number_format($netTotal, 2) }}</td>
                        </tr>
                        @if ($specialDiscount > 0)
                            <tr>
                                <td class="k">Special Discount (1%):</td>
                                <td class="v">- {{ number_format($specialDiscount, 2) }}</td>
                            </tr>
                        @endif
                        <tr class="grand-row">
                            <td class="k">NET PAYABLE:</td>
                            <td class="v">{{ number_format($netPayable, 2) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        {{-- Signatures (anchored to the bottom of the last page, right above the footer) --}}
        <div class="signature-wrap">
            <table class="signature-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="50%">
                        <div class="sign-line">Customer Signature</div>
                    </td>
                    <td width="50%">
                        <div class="sign-line">Authorized Signature</div>
                    </td>
                </tr>
            </table>
        </div>

    </div>

    {{-- Footer (repeats on every PDF page) --}}
    <div class="footer">
        <div class="helpline">Helpline: 01806-023460</div>
        <div class="policy">Once medicines are sold, those won't be returned.</div>
        <div class="thanks">Thank you for shopping with Nimi Enterprise</div>
    </div>
</body>

</html>

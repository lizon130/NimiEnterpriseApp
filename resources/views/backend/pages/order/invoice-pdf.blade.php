<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice - {{ str_replace(['/', '\\'], '', $order->invoice_no) }}</title>

    <style>
        @page {
            size: A4 landscape;
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
            width: 297mm;
            min-height: 210mm;
            background: #ffffff;
            font-family: Arial, Helvetica, sans-serif;
            color: #333;
        }

        body {
            font-size: 11px;
        }

        .print-page {
            width: 297mm;
            min-height: 210mm;
            padding: 10mm 0;
            background: #ffffff;
        }

        .invoice-wrapper {
            width: 148mm;
            margin-left: auto;
            margin-right: auto;
            background: #ffffff;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px double #333;
            margin-bottom: 10px;
            table-layout: fixed;
        }

        .header-table td {
            padding: 3px 0 8px 0;
            vertical-align: middle;
        }

        .header-table .logo-cell {
            width: 15%;
            padding-right: 10px;
        }

        .header-table .logo-cell img {
            width: 55px;
            height: 40px;
            display: block;
        }

        .header-table .meta-cell {
            width: 85%;
            text-align: right;
            vertical-align: middle;
        }

        .invoice-title {
            font-size: 13px;
            font-weight: bold;
            color: #000;
        }

        .date-text {
            font-size: 10px;
            color: #333;
            margin-top: 2px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            background: #f8f9fa;
        }

        .info-table td {
            padding: 8px 10px;
            vertical-align: top;
            border: 1px solid #e9ecef;
        }

        .info-heading {
            font-size: 11px;
            font-weight: bold;
            color: #1a1a2e;
            margin-bottom: 4px;
            padding-bottom: 3px;
            border-bottom: 1px solid #dee2e6;
        }

        .info-row {
            font-size: 10px;
            color: #444;
            line-height: 1.6;
        }

        .info-label {
            font-weight: bold;
            color: #333;
            display: inline-block;
            width: 62px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: 10px;
        }

        .items-table thead th {
            background: #1a1a2e;
            color: #ffffff;
            padding: 6px 5px;
            text-align: center;
            border: 1px solid #1a1a2e;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .items-table thead th.tl {
            text-align: left;
        }

        .items-table tbody td {
            padding: 5px 5px;
            text-align: center;
            border: 1px solid #dee2e6;
            vertical-align: middle;
            font-size: 10px;
        }

        .items-table tbody td.tl {
            text-align: left;
        }

        .items-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .items-table tfoot td {
            padding: 6px 5px;
            border: 1px solid #dee2e6;
            font-weight: bold;
            font-size: 10px;
        }

        .total-row td {
            background: #e9ecef !important;
        }

        .discount-row td {
            background: #fff8e1 !important;
            color: #856404 !important;
        }

        .grand-total-row td {
            background: #1a1a2e !important;
            color: #ffffff !important;
            font-size: 12px !important;
            border-color: #1a1a2e !important;
            letter-spacing: 0.4px;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            border-top: 1px solid #dee2e6;
        }

        .footer-table td {
            text-align: center;
            padding: 2px 0;
            font-size: 9px;
            color: #888;
        }

        .footer-note {
            font-size: 8px;
            color: #aaa;
        }

        .text-primary {
            color: #0d6efd;
        }

        .text-danger {
            color: #dc3545;
        }

        .fw-bold {
            font-weight: bold;
        }

        .part-name {
            color: #888;
            font-size: 9px;
        }

        .empty-row td {
            text-align: center;
            padding: 15px 5px;
            color: #999;
            font-style: italic;
        }

        .no-print {
            display: block;
        }

        .print-btn-area {
            width: 148mm;
            margin: 12px auto;
            text-align: right;
        }

        .print-btn {
            display: inline-block;
            padding: 8px 14px;
            background: #198754;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 13px;
            cursor: pointer;
            border: none;
        }

        @media screen {
            html,
            body {
                width: 100%;
                min-height: 100%;
                background: #eef1f5;
            }

            .print-page {
                width: 297mm;
                min-height: 210mm;
                margin: 20px auto;
                padding: 10mm 0;
                box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
            }
        }

        @media print {
            html,
            body {
                width: 297mm;
                height: 210mm;
                background: #ffffff;
            }

            .no-print {
                display: none !important;
            }

            .print-page {
                width: 297mm;
                height: 210mm;
                padding: 10mm 0;
                margin: 0;
                box-shadow: none;
                page-break-after: avoid;
            }

            .invoice-wrapper {
                width: 148mm;
                margin-left: auto;
                margin-right: auto;
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

    <div class="print-page">
        <div class="invoice-wrapper">

            <table class="header-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="logo-cell">
                        @if (!empty($logoBase64))
                            <img src="{{ $logoBase64 }}" alt="Logo">
                        @else
                            <div style="width: 55px; height: 40px; background: #e9ecef; border: 1px dashed #ccc; text-align: center; line-height: 40px; font-size: 8px; color: #999;">
                                LOGO
                            </div>
                        @endif
                    </td>

                    <td class="meta-cell">
                        <div class="invoice-title">
                            Invoice No:
                            <span style="text-decoration: underline;">
                                {{ str_replace(['/', '\\'], '', $order->invoice_no) }}
                            </span>
                        </div>
                        <div class="date-text">
                            Date: {{ date('d M Y', strtotime($order->date)) }}
                        </div>
                    </td>
                </tr>
            </table>

            <table class="info-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <div class="info-heading">Customer Details</div>
                        <div class="info-row">
                            <span class="info-label">Name:</span> {{ $billing->company ?? 'N/A' }}<br>
                            <span class="info-label">Phone:</span> {{ $billing->phone ?? 'N/A' }}<br>
                            <span class="info-label">Address:</span> {{ $billing->address ?? 'N/A' }}<br>
                        </div>
                    </td>
                </tr>
            </table>

            <table class="items-table" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="30%" class="tl">Product Name</th>
                        <th width="8%">Qty</th>
                        <th width="15%">MRP Rate</th>
                        <th width="15%">Discount</th>
                        <th width="13%">Rate</th>
                        <th width="14%">Net Pay</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($order_details as $key => $item)
                        @php
                            $discountAmount = 0;

                            if ($item->discount > 0) {
                                if ($item->discount_type == 'percent') {
                                    $discountAmount = ($item->unit_price * $item->discount) / 100;
                                } else {
                                    $discountAmount = $item->discount;
                                }
                            }

                            $afterDiscountRate = $item->unit_price - $discountAmount;
                            $netPay = $afterDiscountRate * $item->quantity;
                        @endphp

                        <tr>
                            <td>{{ $key + 1 }}</td>

                            <td class="tl">
                                {{ $item->product->name ?? 'N/A' }}

                                @if ($item->part)
                                    <br>
                                    <span class="part-name">
                                        Part: {{ $item->part->name ?? 'N/A' }}
                                    </span>
                                @endif
                            </td>

                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->unit_price, 2) }}</td>

                            <td>
                                @if ($item->discount > 0)
                                    {{ number_format($discountAmount, 2) }}

                                    @if ($item->discount_type == 'percent')
                                        <br>
                                        <span class="part-name">({{ $item->discount }}%)</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>

                            <td>{{ number_format($afterDiscountRate, 2) }}</td>

                            <td class="text-primary fw-bold">
                                {{ number_format($netPay, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row">
                            <td colspan="7">No items found</td>
                        </tr>
                    @endforelse
                </tbody>

                <tfoot>
                    <tr class="total-row">
                        <td colspan="6" style="text-align:right;">Total Net Amount:</td>
                        <td class="text-primary fw-bold" style="text-align:center;">
                            {{ number_format($order->total_price, 2) }}
                        </td>
                    </tr>

                    @if ($flatDiscount > 0)
                        <tr class="discount-row">
                            <td colspan="6" style="text-align:right;">
                                Flat Discount (0.8% on Net &gt; 5000):
                            </td>
                            <td class="text-danger fw-bold" style="text-align:center;">
                                - {{ number_format($flatDiscount, 2) }}
                            </td>
                        </tr>
                    @endif

                    <tr class="grand-total-row">
                        <td colspan="6" style="text-align:right;">Final Amount:</td>
                        <td style="text-align:center;">
                            {{ number_format($finalAmount, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>

            <table class="footer-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td>Helpline Number: <b>01806-023460</b></td>
                </tr>
                <tr>
                    <td>Once medicine are sold, Those won't be returned</td>
                </tr>
                <tr>
                    <td class="footer-note">
                        Thank You for shopping with Remedy Medicine Services
                    </td>
                </tr>
            </table>

        </div>
    </div>
</body>

</html>
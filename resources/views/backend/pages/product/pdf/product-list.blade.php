<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product List</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 10px;
            color: #222;
            margin: 0;
            padding: 0;
        }

        .page {
            width: 100%;
        }

        /* Header */
        .header {
            width: 100%;
            border-bottom: 3px solid #16437e;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .header .company {
            font-size: 20px;
            font-weight: bold;
            color: #16437e;
        }

        .header .doc-title {
            font-size: 13px;
            color: #555;
            margin-top: 2px;
        }

        .filter-info {
            width: 100%;
            margin-bottom: 8px;
            font-size: 10px;
        }

        .filter-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .filter-info td {
            border: 1px solid #ccc;
            background: #f5f7fa;
            padding: 4px 8px;
        }

        .filter-info .label {
            font-weight: bold;
            color: #16437e;
        }

        /* Main Table */
        table.product-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.product-table thead th {
            background: #16437e;
            color: #fff;
            border: 1px solid #16437e;
            padding: 6px 5px;
            text-align: center;
            font-size: 10px;
            text-transform: uppercase;
        }

        table.product-table tbody td {
            border: 1px solid #ccc;
            padding: 5px;
            vertical-align: middle;
        }

        table.product-table tbody tr:nth-child(even) {
            background: #f5f7fa;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .status-active {
            color: #0a7d32;
            font-weight: bold;
        }

        .status-inactive {
            color: #c0392b;
            font-weight: bold;
        }

        /* Footer */
        .footer-info {
            width: 100%;
            margin-top: 10px;
            font-size: 9px;
            color: #777;
            text-align: center;
        }

        .total-row td {
            background: #e9eef5 !important;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="page">

        <div class="header">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="border: none; text-align: left;">
                        <div class="company">{{ \Helper::getSettings('application_name') ?? 'Nimi Enterprise' }}</div>
                        <div class="doc-title">Product List Report</div>
                    </td>
                    <td style="border: none; text-align: right; font-size: 10px; color: #555;">
                        <div><strong>Date:</strong> {{ date('d M, Y') }}</div>
                        <div><strong>Total Products:</strong> {{ count($products) }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="filter-info">
            <table>
                <tr>
                    <td class="label" style="width: 12%;">Category</td>
                    <td style="width: 38%;">{{ $filter_category ? $filter_category->title : 'All Categories' }}</td>
                    <td class="label" style="width: 12%;">Brand</td>
                    <td style="width: 38%;">{{ $filter_brand ? $filter_brand->title : 'All Brands' }}</td>
                </tr>
            </table>
        </div>

        <table class="product-table">
            <thead>
                <tr>
                    <th style="width: 5%;">SL</th>
                    <th style="width: 12%;">Code (SKU)</th>
                    <th style="width: 26%;">Product Name</th>
                    <th style="width: 14%;">Category</th>
                    <th style="width: 12%;">Brand</th>
                    <th style="width: 11%;">Price</th>
                    <th style="width: 9%;">Discount</th>
                    <th style="width: 11%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $key => $product)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td class="text-center">{{ $product->code ?? '-' }}</td>
                        <td>{{ $product->name ?? '-' }}</td>
                        <td>{{ $product->category->title ?? '-' }}</td>
                        <td>{{ $product->brand->title ?? '-' }}</td>
                        <td class="text-end">{{ number_format($product->price ?? 0, 2) }}</td>
                        <td class="text-center">
                            @if ($product->discount > 0)
                                @if ($product->discount_type == 'percent')
                                    {{ $product->discount }}% OFF
                                @else
                                    {{ number_format($product->discount, 2) }} OFF
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center {{ $product->status == 1 ? 'status-active' : 'status-inactive' }}">
                            {{ $product->status == 1 ? 'Active' : 'Inactive' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer-info">
            Generated on {{ date('d M, Y h:i A') }} &nbsp;|&nbsp; {{ \Helper::getSettings('application_name') ?? 'Nimi Enterprise' }} - Product Management
        </div>

    </div>
</body>
</html>

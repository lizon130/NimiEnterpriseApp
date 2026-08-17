@extends('backend.layout.app')

@section('title', 'Dashboard | ' . (Helper::getSettings('application_name') ?? 'Machine Tool Solution'))

@section('content')
    <style>
        .dashboard-wrapper {
            padding: 24px;
            background: #f8fafc;
            min-height: 100vh;
        }

        .dashboard-title {
            font-size: 26px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 6px;
        }

        .dashboard-subtitle {
            color: #6b7280;
            margin-bottom: 24px;
        }

        .stat-card {
            border: 0;
            border-radius: 18px;
            padding: 22px;
            color: #fff;
            min-height: 150px;
            box-shadow: 0 12px 30px rgba(17, 24, 39, .12);
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .stat-card::after {
            content: "";
            width: 130px;
            height: 130px;
            background: rgba(255, 255, 255, .14);
            border-radius: 50%;
            position: absolute;
            right: -45px;
            top: -45px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            background: rgba(255, 255, 255, .22);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 18px;
        }

        .stat-label {
            font-size: 14px;
            opacity: .95;
            margin-bottom: 6px;
            font-weight: 700;
        }

        .stat-value {
            font-size: 30px;
            font-weight: 900;
            margin: 0;
        }

        .bg-orange {
            background: linear-gradient(135deg, #f85606, #ff8a00);
        }

        .bg-blue {
            background: linear-gradient(135deg, #2563eb, #06b6d4);
        }

        .bg-green {
            background: linear-gradient(135deg, #16a34a, #22c55e);
        }

        .bg-red {
            background: linear-gradient(135deg, #dc2626, #f43f5e);
        }

        .bg-purple {
            background: linear-gradient(135deg, #7c3aed, #a855f7);
        }

        .bg-dark-card {
            background: linear-gradient(135deg, #111827, #374151);
        }

        .info-card {
            background: #fff;
            border: 0;
            border-radius: 18px;
            box-shadow: 0 12px 28px rgba(17, 24, 39, .08);
            overflow: hidden;
        }

        .info-card-header {
            padding: 18px 20px;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 800;
            color: #111827;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            font-size: 13px;
            color: #6b7280;
            font-weight: 800;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .table td {
            vertical-align: middle;
            font-size: 14px;
        }

        .badge-soft-success {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-soft-danger {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-soft-warning {
            background: #fef3c7;
            color: #b45309;
        }

        @media(max-width: 768px) {
            .dashboard-wrapper {
                padding: 16px;
            }

            .dashboard-title {
                font-size: 22px;
            }

            .stat-card {
                min-height: 135px;
                padding: 18px;
            }

            .stat-value {
                font-size: 24px;
            }

            .table-responsive {
                border-radius: 0 0 18px 18px;
            }
        }
    </style>

    <div class="dashboard-wrapper">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
            <div>
                <h4 class="dashboard-title">Dashboard</h4>
                <p class="dashboard-subtitle">Product, stock, order and sales overview</p>
            </div>
        </div>

        <div class="row g-4 mb-4">
            @if (Auth::user()->role != 2 && Auth::user()->role != 4 && Auth::user()->role != 5)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="stat-card bg-purple">
                        <div class="stat-icon"><i class="fa-solid fa-people-group"></i></div>
                        <p class="stat-label">Total Seller</p>
                        <h3 class="stat-value">{{ $total_reseller ?? 0 }}</h3>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="stat-card bg-blue">
                        <div class="stat-icon"><i class="fa-solid fa-people-line"></i></div>
                        <p class="stat-label">Total Distributor</p>
                        <h3 class="stat-value">{{ $total_distributor ?? 0 }}</h3>
                    </div>
                </div>
            @endif

            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="stat-card bg-orange">
                    <div class="stat-icon"><i class="fa-solid fa-box"></i></div>
                    <p class="stat-label">Total Products</p>
                    <h3 class="stat-value">{{ $total_product ?? 0 }}</h3>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="stat-card bg-green">
                    <div class="stat-icon"><i class="fa-solid fa-warehouse"></i></div>
                    <p class="stat-label">Total Stock</p>
                    <h3 class="stat-value">{{ $totalStock ?? 0 }}</h3>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="stat-card bg-red">
                    <div class="stat-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <p class="stat-label">Low Stock Products</p>
                    <h3 class="stat-value">{{ $lowStock ?? 0 }}</h3>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="stat-card bg-dark-card">
                    <div class="stat-icon"><i class="fa-solid fa-circle-xmark"></i></div>
                    <p class="stat-label">Out of Stock</p>
                    <h3 class="stat-value">{{ $outOfStock ?? 0 }}</h3>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="stat-card bg-blue">
                    <div class="stat-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                    <p class="stat-label">Total Orders</p>
                    <h3 class="stat-value">{{ $total_order ?? 0 }}</h3>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="stat-card bg-purple">
                    <div class="stat-icon"><i class="fa-solid fa-clock"></i></div>
                    <p class="stat-label">Pending Orders</p>
                    <h3 class="stat-value">{{ $pending_order ?? 0 }}</h3>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="stat-card bg-green">
                    <div class="stat-icon"><i class="fa-solid fa-hand-holding-dollar"></i></div>
                    <p class="stat-label">
                        @if (Auth::user()->role != 2 && Auth::user()->role != 4 && Auth::user()->role != 5)
                            Total Sales
                        @else
                            Total Purchase
                        @endif
                    </p>
                    <h3 class="stat-value">$ {{ number_format($total_sale ?? 0, 2) }}</h3>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="stat-card bg-orange">
                    <div class="stat-icon"><i class="fa-solid fa-calendar-day"></i></div>
                    <p class="stat-label">Today Sales</p>
                    <h3 class="stat-value">$ {{ number_format($today_sale ?? 0, 2) }}</h3>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="stat-card bg-blue">
                    <div class="stat-icon"><i class="fa-solid fa-plus"></i></div>
                    <p class="stat-label">Today Stock In</p>
                    <h3 class="stat-value">{{ $todayStockIn ?? 0 }}</h3>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="stat-card bg-red">
                    <div class="stat-icon"><i class="fa-solid fa-minus"></i></div>
                    <p class="stat-label">Today Stock Out</p>
                    <h3 class="stat-value">{{ $todayStockOut ?? 0 }}</h3>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="stat-card bg-dark-card">
                    <div class="stat-icon"><i class="fa-solid fa-envelope-open-text"></i></div>
                    <p class="stat-label">Inquiry Request</p>
                    <h3 class="stat-value">{{ $inquiry_request ?? 0 }}</h3>
                </div>
            </div>
        </div>

        @if (Auth::user()->role == 1)
            <div class="row g-4">
                <div class="col-xl-6">
                    <div class="info-card">
                        <div class="info-card-header">
                            <span><i class="fa-solid fa-triangle-exclamation text-warning me-2"></i>Low Stock
                                Products</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Code</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($lowStockProducts ?? [] as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->code }}</td>
                                            <td><strong>{{ optional($product->stock)->quantity ?? 0 }}</strong></td>
                                            <td>
                                                @if ((optional($product->stock)->quantity ?? 0) <= 0)
                                                    <span class="badge badge-soft-danger">Out</span>
                                                @else
                                                    <span class="badge badge-soft-warning">Low</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No low stock products.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="info-card">
                        <div class="info-card-header">
                            <span><i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>Recent Stock History</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Type</th>
                                        <th>Qty</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentStockHistory ?? [] as $history)
                                        <tr>
                                            <td>{{ optional($history->product)->name ?? '-' }}</td>
                                            <td>
                                                @if ($history->type == 'in')
                                                    <span class="badge badge-soft-success">In</span>
                                                @else
                                                    <span class="badge badge-soft-danger">Out</span>
                                                @endif
                                            </td>
                                            <td><strong>{{ $history->quantity }}</strong></td>
                                            <td>{{ $history->reason ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No stock history.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12">
                    <div class="info-card">
                        <div class="info-card-header">
                            <span><i class="fa-solid fa-cart-shopping text-success me-2"></i>Recent Orders</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>User</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentOrders ?? [] as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ optional($order->company)->name ?? '-' }}</td>
                                            <td>$ {{ number_format($order->total_price ?? 0, 2) }}</td>
                                            <td>
                                                @if ($order->status == 0)
                                                    <span class="badge badge-soft-warning">Pending</span>
                                                @elseif($order->status == 1)
                                                    <span class="badge badge-soft-success">Processing</span>
                                                @elseif($order->status == 2)
                                                    <span class="badge badge-soft-success">Completed</span>
                                                @else
                                                    <span class="badge badge-soft-danger">Cancelled</span>
                                                @endif
                                            </td>
                                            <td>{{ $order->created_at ? $order->created_at->format('d M Y') : '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">No recent orders.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        @endif
    </div>
@endsection
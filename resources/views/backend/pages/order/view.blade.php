<div class="modal-header bg-primary text-white">
    <h5 class="modal-title" id="exampleModalLabel">
        <i class="fa-solid fa-receipt"></i> Order Details - #{{ $order->order_id ?? $order->id }}
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <!-- Order Status Badge -->
        <div class="col-12 mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Order Status:</h6>
                @php
                    $statusBadge = '';
                    if($order->status == 0) $statusBadge = '<span class="badge bg-warning fs-6">🆕 New Order</span>';
                    elseif($order->status == 1) $statusBadge = '<span class="badge bg-info fs-6">🚚 Shipping</span>';
                    elseif($order->status == 2) $statusBadge = '<span class="badge bg-success fs-6">✅ Delivered</span>';
                    else $statusBadge = '<span class="badge bg-danger fs-6">❌ Rejected</span>';
                @endphp
                {!! $statusBadge !!}
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="col-md-6">
            <!-- Order Information -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fa-solid fa-info-circle"></i> Order Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td width="40%"><strong>Order ID:</strong></td>
                            <td>{{ $order->order_id ?? $order->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Order Date:</strong></td>
                            <td>{{ date('d M Y, h:i A', strtotime($order->date)) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Payment Status:</strong></td>
                            <td>
                                @if($order->payment_status == 1)
                                    <span class="badge bg-success">Paid</span>
                                @else
                                    <span class="badge bg-danger">Pending</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Total Amount:</strong></td>
                            <td><strong class="text-primary fs-5">৳{{ number_format($order->total_price, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
              <!-- Partner/Company Information -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fa-solid fa-building"></i> Partner Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td width="40%"><strong>Company:</strong></td>
                            <td>{{ $billing->company ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Contact Person:</strong></td>
                            <td>{{ $billing->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $billing->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Phone:</strong></td>
                            <td>{{ $billing->phone ?? 'N/A' }}</td>
                        </tr>
                         <tr>
                            <td width="40%"><strong>Address:</strong></td>
                            <td>{{ $billing->address ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Items Table -->
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fa-solid fa-box"></i> Order Items</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Type</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Discount</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order_details as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <strong>{{ $item->product->name ?? 'N/A' }}</strong>
                                            @if($item->part)
                                                <br><small class="text-muted">Part: {{ $item->part->name ?? 'N/A' }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->type == 'part')
                                                <span class="badge bg-info">Part</span>
                                            @else
                                                <span class="badge bg-primary">Product</span>
                                            @endif
                                        </td>
                                        <td>৳{{ number_format($item->unit_price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>
                                            @if($item->discount > 0)
                                                @if($item->discount_type == 'percent')
                                                    {{ $item->discount }}% off
                                                @else
                                                    ৳{{ number_format($item->discount, 2) }} off
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-primary fw-bold">৳{{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No items found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="6" class="text-end fw-bold">Total Amount:</td>
                                    <td class="fw-bold fs-5 text-primary">৳{{ number_format($order->total_price, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="fa-solid fa-times"></i> Close
    </button>
    @if($order->status != 2 && $order->status != 3)
        <a href="{{ route('admin.order.edit.status', $order->id) }}" class="btn btn-warning">
            <i class="fa-solid fa-truck"></i> Update Status
        </a>
    @endif
    <button type="button" class="btn btn-primary" onclick="window.print();">
        <i class="fa-solid fa-print"></i> Print
    </button>
</div>

<style>
    @media print {
        .modal-header, .modal-footer, .btn-close, .btn {
            display: none !important;
        }
        .modal {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            margin: 0;
            padding: 0;
        }
        .modal-content {
            border: none;
            box-shadow: none;
        }
        .card {
            break-inside: avoid;
        }
    }
</style>

@extends('backend.layout.app')
@section('title', 'Stock Management')
@section('content')

    <div class="container-fluid px-4">
        <h4 class="mt-2">Stock Management</h4>

        <div class="card my-2">
            <div class="card-header">
                <div class="row">
                    <div class="col-12 d-flex justify-content-between">
                        <h5>Product Stock List</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#initializeModal">
                            <i class="fa-solid fa-plus"></i> Initialize Stock
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered" id="stockTable">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Code</th>
                            <th>Product Name</th>
                            <th>Brand</th>
                            <th>Current Stock</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Initialize Modal - Simple Version --}}
<div class="modal fade" id="initializeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="initializeForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Initialize Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Product <span class="text-danger">*</span></label>
                        <select name="product_id" class="form-control product_select2" required>
                            <option value="">Select Product</option>
                            @php
                                use App\Models\Product;
                                $products = Product::all();
                            @endphp
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }} ({{ $product->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Initial Quantity</label>
                        <input type="number" name="quantity" class="form-control" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Initialize</button>
                </div>
            </form>
        </div>
    </div>
</div>

    {{-- Adjust Modal --}}
    <div class="modal fade" id="adjustModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="adjustForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Adjust Stock</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="product_id" id="adjust_product_id">
                        <div class="alert alert-info" id="stockInfo"></div>

                        <div class="form-group mb-3">
                            <label>Type</label>
                            <select name="type" class="form-control" required>
                                <option value="add">➕ Add Stock (In)</option>
                                <option value="remove">➖ Remove Stock (Out)</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Quantity</label>
                            <input type="number" name="quantity" class="form-control" min="1" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Reason</label>
                            <select name="reason" class="form-control" required>
                                <option value="purchase">Purchase</option>
                                <option value="sale">Sale</option>
                                <option value="return">Return</option>
                                <option value="damage">Damage</option>
                                <option value="adjustment">Adjustment</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update Stock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- History Modal --}}
    <div class="modal fade" id="historyModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Stock History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="historyContent">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th>Reason</th>
                                <th>Notes</th>
                                <th>By</th>
                            </tr>
                        </thead>
                        <tbody id="historyTable"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('footer')
        <script>
            $(document).ready(function() {
                var table = $('#stockTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('admin.stock.get.list') }}",
                        type: 'GET'
                    },
                    columns: [{
                            data: 'thumbnail',
                            name: 'thumbnail',
                            orderable: false
                        },
                        {
                            data: 'code',
                            name: 'code'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'brand_title',
                            name: 'brand_title'
                        }, // Changed from 'brand.title' to 'brand_title'
                        {
                            data: 'current_stock',
                            name: 'current_stock',
                            orderable: false
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false
                        }
                    ]
                });

                // Initialize stock
                $('#initializeForm').on('submit', function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('admin.stock.initialize') }}",
                        type: "POST",
                        data: $(this).serialize(),
                        success: function(res) {
                            $.toast({
                                heading: 'Success',
                                text: res.message,
                                icon: 'success'
                            });
                            $('#initializeModal').modal('hide');
                            $('#initializeForm')[0].reset();
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            $.toast({
                                heading: 'Error',
                                text: xhr.responseJSON?.message || 'Error',
                                icon: 'error'
                            });
                        }
                    });
                });

                // Adjust stock button
                $(document).on('click', '.adjust_stock', function() {
                    let id = $(this).data('id');
                    let name = $(this).data('name');
                    let stock = $(this).data('stock');

                    $('#adjust_product_id').val(id);
                    $('#stockInfo').html(`<strong>${name}</strong><br>Current Stock: ${stock}`);
                    $('#adjustModal').modal('show');
                });

                // Adjust form submit
                $('#adjustForm').on('submit', function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('admin.stock.adjust') }}",
                        type: "POST",
                        data: $(this).serialize(),
                        success: function(res) {
                            $.toast({
                                heading: 'Success',
                                text: res.message,
                                icon: 'success'
                            });
                            $('#adjustModal').modal('hide');
                            $('#adjustForm')[0].reset();
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            $.toast({
                                heading: 'Error',
                                text: xhr.responseJSON?.error || 'Error',
                                icon: 'error'
                            });
                        }
                    });
                });

                // History button
                $(document).on('click', '.history_btn', function() {
                    let id = $(this).data('id');
                    $.ajax({
                        url: "{{ url('admin/stock/history') }}/" + id,
                        type: "GET",
                        success: function(res) {
                            let html = '';
                            if (res.history && res.history.length > 0) {
                                $.each(res.history, function(i, h) {
                                    let badge = h.type == 'in' ?
                                        '<span class="badge bg-success">+IN</span>' :
                                        '<span class="badge bg-danger">-OUT</span>';
                                    html += `<tr>
                            <td>${new Date(h.created_at).toLocaleString()}</td>
                            <td>${badge}</td>
                            <td class="${h.type == 'in' ? 'text-success' : 'text-danger'} fw-bold">${h.quantity}</td>
                            <td>${h.reason}</td>
                            <td>${h.notes || '-'}</td>
                            <td>${h.creator?.name || '-'}</td>
                        </tr>`;
                                });
                            } else {
                                html =
                                    '<tr><td colspan="6" class="text-center">No history found</td></tr>';
                            }
                            $('#historyTable').html(html);
                            $('#historyModal').modal('show');
                        }
                    });
                });

                $('.product_select2').select2({
                    dropdownParent: $('#initializeModal'),
                    placeholder: 'Select a product',
                    allowClear: true,
                    width: '100%'
                });
            });
        </script>
    @endpush
@endsection

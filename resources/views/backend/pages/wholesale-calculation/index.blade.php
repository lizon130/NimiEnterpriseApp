@extends('backend.layout.app')
@section('title', 'Wholesale Calculation | ' . Helper::getSettings('application_name') ?? 'Machine Tool Solution')
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Wholesale Calculation Management</h4>

        <!-- Summary Cards -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Purchase</h5>
                        <h3 class="mb-0">৳ {{ number_format($totalPurchase ?? 0, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Total Sale</h5>
                        <h3 class="mb-0">৳ {{ number_format($totalSale ?? 0, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card my-2">
            <div class="card-header">
                <div class="row">
                    <div class="col-12 d-flex justify-content-between">
                        <div class="d-flex align-items-center"><h5 class="m-0">Wholesale Calculation List</h5></div>
                        <!-- Use existing brand.create permission -->
                        @if (Helper::hasRight('brand.create'))
                            <button type="button" class="btn btn-primary btn-create-user create_form_btn" data-bs-toggle="modal" data-bs-target="#createModal">
                                <i class="fa-solid fa-plus"></i> Add
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Purchase Amount</th>
                            <th>Sale Amount</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr class="table-info fw-bold">
                            <td class="text-center">GRAND TOTAL</td>
                            <td id="totalPurchase">৳ 0.00</td>
                            <td id="totalSale">৳ 0.00</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @include('backend.pages.wholesale-calculation.modal')

    @push('footer')
        <script type="text/javascript">
            function getData() {
                var table = jQuery('#dataTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('admin/wholesale-calculation/get/list') }}",
                        type: 'GET',
                        dataSrc: function (json) {
                            // Update totals in footer
                            if (json.totalPurchase !== undefined) {
                                $('#totalPurchase').html('৳ ' + parseFloat(json.totalPurchase).toFixed(2));
                            }
                            if (json.totalSale !== undefined) {
                                $('#totalSale').html('৳ ' + parseFloat(json.totalSale).toFixed(2));
                            }
                            return json.data;
                        }
                    },
                    aLengthMenu: [
                        [25, 50, 100, 500, 5000, -1],
                        [25, 50, 100, 500, 5000, "All"]
                    ],
                    iDisplayLength: 25,
                    "order": [
                        [0, 'desc']
                    ],
                    columns: [
                        {
                            data: 'date',
                            name: 'date'
                        },
                        {
                            data: 'purchase_amount',
                            name: 'purchase_amount'
                        },
                        {
                            data: 'sale_amount',
                            name: 'sale_amount'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            "className": "text-center w-15"
                        },
                    ],
                    "footerCallback": function (row, data, start, end, display) {
                        // Alternative method to calculate totals from displayed data
                        var api = this.api();

                        // Remove formatting to get integer data for summation
                        var intVal = function (i) {
                            return typeof i === 'string' ?
                                i.replace(/[^\d.]/g, '') * 1 :
                                typeof i === 'number' ? i : 0;
                        };

                        // Total purchase over all pages
                        var totalPurchase = api
                            .column(1, { page: 'current' })
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Total sale over all pages
                        var totalSale = api
                            .column(2, { page: 'current' })
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Update footer
                        $(api.column(1).footer()).html('৳ ' + totalPurchase.toFixed(2));
                        $(api.column(2).footer()).html('৳ ' + totalSale.toFixed(2));
                    }
                });
            }
            getData();

            $(document).on('click', '#createSubmitBtn', function(e) {
                e.preventDefault();
                let form = document.getElementById('createForm');
                var formData = new FormData(form);
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: $('#createForm').attr('action'),
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $.toast({
                            heading: 'Success',
                            text: response.message,
                            position: 'top-center',
                            icon: 'success'
                        })
                        $('#dataTable').DataTable().destroy();
                        getData();
                        $('#createModal').modal('hide');
                        $('#createForm')[0].reset();
                    },
                    error: function (xhr) {
                        let errorMessage = '';
                        if (xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                errorMessage += ('' + value + '<br>');
                            });
                        } else {
                            errorMessage = xhr.responseJSON.message || 'Something went wrong!';
                        }
                        $('#createForm .server_side_error').empty();
                        $('#createForm .server_side_error').html('<div class="alert alert-danger" role="alert">' + errorMessage + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    },
                })
            })

            $(document).on('click', '.edit_btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                $.ajax({
                    url: "{{ url('/admin/wholesale-calculation/edit/') }}/" + id,
                    type: "GET",
                    dataType: "html",
                    success: function (data) {
                        $('#editModal .modal-content').html(data);
                        $('#editModal').modal('show');
                    },
                    error: function() {
                        $.toast({
                            heading: 'Error',
                            text: 'Failed to load edit form',
                            position: 'top-center',
                            icon: 'error'
                        });
                    }
                })
            });

            $(document).on('click', '#editSubmitBtn', function(e) {
                e.preventDefault();
                let form = document.getElementById('editForm');
                var formData = new FormData(form);
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: $('#editForm').attr('action'),
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $.toast({
                            heading: 'Success',
                            text: response.message,
                            position: 'top-center',
                            icon: 'success'
                        })
                        $('#dataTable').DataTable().destroy();
                        getData();
                        $('#editModal').modal('hide');
                    },
                    error: function (xhr) {
                        let errorMessage = '';
                        if (xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                errorMessage += ('' + value + '<br>');
                            });
                        } else {
                            errorMessage = xhr.responseJSON.message || 'Something went wrong!';
                        }
                        $('#editForm .server_side_error').empty();
                        $('#editForm .server_side_error').html('<div class="alert alert-danger" role="alert">' + errorMessage + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    },
                })
            })

            $(document).on('click', '.delete_btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('/admin/wholesale-calculation/delete/') }}/" + id,
                            type: "GET",
                            dataType: "json",
                            success: function (data) {
                                if (data.success) {
                                    $.toast({
                                        heading: 'Success',
                                        text: data.success,
                                        position: 'top-center',
                                        icon: 'success'
                                    })
                                } else {
                                    $.toast({
                                        heading: 'Error',
                                        text: data.error,
                                        position: 'top-center',
                                        icon: 'error'
                                    })
                                }
                                $('#dataTable').DataTable().destroy();
                                getData();
                            }
                        })
                    }
                })
            })
        </script>
    @endpush
@endsection

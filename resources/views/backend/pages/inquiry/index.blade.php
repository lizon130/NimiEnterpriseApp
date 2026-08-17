@extends('backend.layout.app')
@section('title', 'Inquiry | '.Helper::getSettings('application_name') ?? 'Machine Tool Solution')
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Inquiry Management</h4>

        <div class="card my-2">
            <div class="card-body pb-0">
                <form method="" id="filter_form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="date" class="form-control" name="date" id="date" >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" name="request_by" id="request_by" placeholder="Requested By" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select name="status" id="status" class="form-control">
                                    <option value="">Select Status</option>
                                    <option value="3">New</option>
                                    <option value="1">Complete</option>
                                    <option value="2">Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group text-end mt-2">
                                <button type="submit" id="filterBtn" name="submit" class="btn btn-primary"><i class="feather icon-file mr-2"></i> Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card my-2">
            <div class="card-header">
                <div class="row ">
                    <div class="col-12 d-flex justify-content-between">
                        <div class="d-flex align-items-center"><h5 class="m-0">Inquiry List</h5></div>
                        @if (Helper::hasRight('inquiry.create'))
                            <button type="button" class="btn btn-primary btn-create-user create_form_btn" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa-solid fa-plus"></i> Add</button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Requested By</th>
                            <th>Inquiry Date</th>
                            <th>Company Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            @auth
                            @if(auth()->user()->role !== 4 && auth()->user()->role !== 5)
                                <th class="w-15">Action</th>
                            @endif
                            @endauth
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('backend.pages.inquiry.modal')
    @push('footer')
        <script type="text/javascript">
            function getinquirys(date = null, request_by = null, status = null){
                var table = jQuery('#dataTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('admin/inquiry/get/list') }}",
                        type: 'GET',
                        data:{
                            'date': date,
                            'request_by': request_by,
                            'status': status,
                        },
                    },
                    aLengthMenu: [
                        [25, 50, 100, 500, 5000, -1],
                        [25, 50, 100, 500, 5000, "All"]
                    ],
                    iDisplayLength: 25,
                    "order": [
                        [ 1, 'asc' ]
                    ],
                    columns: [
                        {
                            data: 'request_by',
                            name: 'request_by'
                        },
                        {
                            data: 'date',
                            name: 'date'
                        },
                        {
                            data: 'company',
                            name: 'company'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },

                        {
                            data: 'status',
                            name: 'status',
                            "className": "text-center w-10"
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            "className": "text-center w-10",
                            visible: {{ auth()->user()->role !== 4 && auth()->user()->role !== 5 ? 'true' : 'false' }}
                        },
                    ]
                });
            }
            getinquirys();

            $(document).on('click', '#filterBtn', function(e) {
                e.preventDefault();
                let date = $('#filter_form #date').val();
                let request_by = $('#filter_form #request_by').val();
                let status = $('#filter_form #status').val();

                $('#dataTable').DataTable().destroy();
                getinquirys(date, request_by, status);
            })


            // add row
            function addRow(modalname = null){
                let number = $('#'+modalname+' tbody tr:last').attr('data-row');
                if (number == null) {
                    number = 1;
                }
                $.ajax({
                    url: "{{  url('/admin/inquiry/row/') }}/"+number,
                    type: "GET",
                    dataType: "html",
                    success: function (data) {
                        $('#'+modalname+' .products_area tbody').append(data);
                    }
                })
            }

            $(document).on('click', '#createModal #addProduct', function(e) {
                e.preventDefault();
                addRow('createModal');
            })

            $(document).on('click', '#createModal .remove_product', function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
                let row = $(this).attr('data-row');
                $("#createModal .products_area tbody ."+id).remove();
                calculateTotal('createModal');
            })

            $(document).on('click', '#editModal #addProduct', function(e) {
                e.preventDefault();
                addRow('editModal');
            })

            $(document).on('click', '#editModal .remove_product', function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
                let row = $(this).attr('data-row');
                $("#editModal .products_area tbody ."+id).remove();
                calculateTotal('editModal');
            })

            // on change events

            function getPartnerCompany(user_id, main_div){
                $.ajax({
                    url: "{{  url('/admin/inquiry/get/company/') }}/"+user_id,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#'+main_div+' input[name=company]').val(data.name);
                        $('#'+main_div+' input[name=name]').val(data.contact_name);
                        $('#'+main_div+' input[name=phone]').val(data.phone_number);
                        $('#'+main_div+' input[name=email]').val(data.email);
                        $('#'+main_div+' input[name=address]').val(data.address);
                        $('#'+main_div+' input[name=post_code]').val(data.post_code);
                        $('#'+main_div+' input[name=city]').val(data.city);
                        $('#'+main_div+' input[name=state]').val(data.state);
                        $('#'+main_div+' input[name=country]').val(data.country);
                    }
                })
            }

            $(document).on('change', '#createModal .user_id', function(e) {
                e.preventDefault();
                let id = $(this).val();
                if(id != ''){
                    getPartnerCompany(id, 'createModal');
                }
            })

            $(document).on('change', '#editModal .user_id', function(e) {
                e.preventDefault();
                let id = $(this).val();
                if(id != ''){
                    getPartnerCompany(id, 'editModal');
                }
            })

            function getProduct(product_id, main_div, row){
                $.ajax({
                    url: "{{  url('/admin/inquiry/get/product') }}",
                    type: "GET",
                    data:{
                        'product_id':product_id
                    },
                    dataType: "json",
                    success: function (data) {
                        $('#'+main_div+' .price'+row).val(data.price);
                    }
                })
            }

            $(document).on('change', '#createModal .product_select', function(e) {
                e.preventDefault();
                let id = $(this).val();
                let row = $(this).attr('data-row');
                if(id != ''){
                    getProduct(id,'createModal', row);
                    calculateTotal('createModal');
                }
            })

            $(document).on('change', '#editModal .product_select', function(e) {
                e.preventDefault();
                let id = $(this).val();
                let row = $(this).attr('data-row');
                if(id != ''){
                    getProduct(id,'editModal', row);
                    calculateTotal('editModal');
                }
            })


            function calculateTotal(main_div) {
                let grand_total = 0;
                $("#"+main_div+' tbody tr').each(function( index ) {
                    let row = $(this).attr('data-row');
                    let price = Number($('#'+main_div+' .price'+row).val());
                    let qty = Number($('#'+main_div+' .qty'+row).val());
                    let discount_type = $('#'+main_div+' .discount_type'+row).val();
                    let discount = Number($('#'+main_div+' .discount'+row).val());
                    let subtotal = price * qty;

                    if(discount_type == 'percent') {
                        subtotal = subtotal - (subtotal * discount) / 100;
                    }else if(discount_type == 'amount') {
                        subtotal = subtotal - discount;
                    }

                    $('#'+main_div+' .subtotal'+row).val(subtotal);

                    grand_total = grand_total + subtotal;
                });

                $('#'+main_div+' .total_price').val(grand_total);
            }


            $(document).on('change', '#createModal .qty', function(e) {
                e.preventDefault();
                calculateTotal('createModal');
            })

            $(document).on('keyup', '#createModal .qty', function(e) {
                e.preventDefault();
                calculateTotal('createModal');
            })

            $(document).on('change', '#createModal .discount_type', function(e) {
                e.preventDefault();
                calculateTotal('createModal');
            })

            $(document).on('keyup', '#createModal .discount', function(e) {
                e.preventDefault();
                calculateTotal('createModal');
            })

            // edit modal on change event

            $(document).on('change', '#editModal .qty', function(e) {
                e.preventDefault();
                calculateTotal('editModal');
            })

            $(document).on('keyup', '#createModal .qty', function(e) {
                e.preventDefault();
                calculateTotal('editModal');
            })

            $(document).on('change', '#editModal .discount_type', function(e) {
                e.preventDefault();
                calculateTotal('editModal');
            })

            $(document).on('keyup', '#editModal .discount', function(e) {
                e.preventDefault();
                calculateTotal('editModal');
            })

            // next button
            $(document).on('click', '#createModal .next_btn', function(e) {
                e.preventDefault();
                let go_next_step = true;
                if ($(this).attr('data-check-area') && $(this).attr('data-check-area').trim() !== '') {
                    go_next_step = check_validation_Form('#createModal .'+$(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    let step = $(this).attr('data-step-open');
                    let step_btn = $(this).attr('data-step-button');

                    $('#createModal .step').removeClass('active show');
                    $('#createModal .step_btn').removeClass('d-block');
                    $('#createModal .step_btn').addClass('d-none');

                    $('#createModal .'+step).addClass('active show');
                    $('#createModal .'+step_btn).removeClass('d-none');
                    $('#createModal .'+step_btn).addClass('d-block');
                }
            })

            $(document).on('click', '#editModal .next_btn', function(e) {
                e.preventDefault();
                let go_next_step = true;
                if ($(this).attr('data-check-area') && $(this).attr('data-check-area').trim() !== '') {
                    go_next_step = check_validation_Form('#editModal .'+$(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    let step = $(this).attr('data-step-open');
                    let step_btn = $(this).attr('data-step-button');

                    $('#editModal .step').removeClass('active show');
                    $('#editModal .step_btn').removeClass('d-block');
                    $('#editModal .step_btn').addClass('d-none');

                    $('#editModal .'+step).addClass('active show');
                    $('#editModal .'+step_btn).removeClass('d-none');
                    $('#editModal .'+step_btn).addClass('d-block');
                }
            })

            $(document).on('click', '#createInquiryBtn', function(e) {
                e.preventDefault();
                let go_next_step = true;
                if ($(this).attr('data-check-area') && $(this).attr('data-check-area').trim() !== '') {
                    go_next_step = check_validation_Form('#createModal .'+$(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    $('#createInquiryBtn').prop('disabled', true);
                    let form = document.getElementById('createInquiryForm');
                    var formData = new FormData(form);
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: $('#createInquiryForm').attr('action'),
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
                            getinquirys();
                            $('#createModal').modal('hide');
                            $('#createInquiryBtn').prop('disabled', false);
                        },
                        error: function (xhr) {
                            $('#createInquiryBtn').prop('disabled', false);
                            let errorMessage = '';
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                errorMessage +=(''+value+'<br>');
                            });
                            $('#createInquiryForm .server_side_error').empty();
                            $('#createInquiryForm .server_side_error').html('<div class="alert alert-danger" role="alert">'+errorMessage+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        },
                    })
                }
            })

            $(document).on('click', '.edit_btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                $.ajax({
                    url: "{{  url('/admin/inquiry/edit/') }}/"+id,
                    type: "GET",
                    dataType: "html",
                    success: function (data) {
                        $('#editModal .modal-content').html(data);
                        $('#editModal').modal('show');
                    }
                })
            });

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
                            url: "{{  url('/admin/inquiry/delete/') }}/"+id,
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
                                getinquirys();
                            }
                        })

                    }
                })
            })

            $(document).on('click', '#editInquiryBtn', function(e) {
                e.preventDefault();
                let go_next_step = true;
                if ($(this).attr('data-check-area') && $(this).attr('data-check-area').trim() !== '') {
                    go_next_step = check_validation_Form('#editModal .'+$(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    $('#editInquiryBtn').prop('disabled', true);
                    let form = document.getElementById('editInquiryForm');
                    var formData = new FormData(form);
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: $('#editInquiryForm').attr('action'),
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
                            getinquirys();
                            $('#editModal').modal('hide');
                            $('#editInquiryBtn').prop('disabled', false);
                        },
                        error: function (xhr) {
                            $('#editInquiryBtn').prop('disabled', false);
                            let errorMessage = '';
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                errorMessage +=(''+value+'<br>');
                            });
                            $('#editInquiryForm .server_side_error').empty();
                            $('#editInquiryForm .server_side_error').html('<div class="alert alert-danger" role="alert">'+errorMessage+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        },
                    })
                }
            })

            $(document).on('click', '#sendEmailInquiryBtn', function(e) {
                e.preventDefault();
                $('#sendEmailInquiryBtn').prop('disabled', true);
                let form = document.getElementById('editInquiryForm');
                var formData = new FormData(form);
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: $('#editInquiryForm').attr('data-email-route'),
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
                        getinquirys();
                        $('#editModal').modal('hide');
                        $('#sendEmailInquiryBtn').prop('disabled', false);
                    },
                    error: function (xhr) {
                        $('#sendEmailInquiryBtn').prop('disabled', false);
                        let errorMessage = '';
                        $.each(xhr.responseJSON.errors, function(key,value) {
                            errorMessage +=(''+value+'<br>');
                        });
                        $('#editInquiryForm .server_side_error').empty();
                        $('#editInquiryForm .server_side_error').html('<div class="alert alert-danger" role="alert">'+errorMessage+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    },
                })
            })

            $(document).on('click', '.status_change_btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                $.ajax({
                    url: "{{  url('/admin/inquiry/edit/status/') }}/"+id,
                    type: "GET",
                    dataType: "html",
                    success: function (data) {
                        $('#statusModal .modal-content').html(data);
                        $('#statusModal').modal('show');
                    }
                })
            });

            $(document).on('click', '#statusOrderBtn', function(e) {
                e.preventDefault();
                $('#statusOrderBtn').prop('disabled', true);
                let form = document.getElementById('statusOrderForm');
                var formData = new FormData(form);
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: $('#statusOrderForm').attr('action'),
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
                        getinquirys();
                        $('#statusModal').modal('hide');
                        $('#statusOrderBtn').prop('disabled', false);
                    },
                    error: function (xhr) {
                        $('#statusOrderBtn').prop('disabled', false);
                        let errorMessage = '';
                        $.each(xhr.responseJSON.errors, function(key,value) {
                            errorMessage +=(''+value+'<br>');
                        });
                        $('#statusOrderForm .server_side_error').empty();
                        $('#statusOrderForm .server_side_error').html('<div class="alert alert-danger" role="alert">'+errorMessage+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    },
                })
            })
        </script>
    @endpush
@endsection

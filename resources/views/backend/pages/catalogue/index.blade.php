@extends('backend.layout.app')
@section('title', 'Catalogue | '.Helper::getSettings('application_name') ?? 'Machine Tool Solution')
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Catalogue Management</h4>
        
        <div class="card my-2">
            <div class="card-body pb-0">
                <form method="" id="filter_form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control" name="title" id="title" placeholder="Catalogue Title" > 
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select name="category" class="form-control" id="category">
                                    <option value="">Select Category</option>
                                    @foreach ($categorys as $item)
                                        <option value="{{ $item->id }}">{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select name="brand" class="form-control" id="brand">
                                    <option value="">Select Brand</option>
                                    @foreach ($brands as $item)
                                        <option value="{{ $item->id }}">{{ $item->title }}</option>
                                    @endforeach
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
                        <div class="d-flex align-items-center"><h5 class="m-0">Catalogue List</h5></div>
                        @if (Helper::hasRight('catalogue.create'))
                            <button type="button" class="btn btn-primary btn-create-user create_form_btn" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa-solid fa-plus"></i> Add</button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('backend.pages.catalogue.modal')
    @push('footer')
        <script type="text/javascript">
            $('#product_id').select2({
                dropdownParent: $('#createModal')
            });
            function getcatalogue(category = null, title = null, brand = null){
                var table = jQuery('#dataTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('admin/catalogue/get/list') }}",
                        type: 'GET',
                        data:{
                            'category': category,
                            'title': title,
                            'brand': brand
                        },
                    },
                    aLengthMenu: [
                        [25, 50, 100, 500, 5000, -1],
                        [25, 50, 100, 500, 5000, "All"]
                    ],
                    iDisplayLength: 25,
                    "order": [
                        [ 0, 'asc' ]
                    ],
                    columns: [
                        {
                            data: 'title',
                            name: 'title'
                        },
                        {
                            data: 'type',
                            name: 'type',
							"className": "text-capitalize"
                        },
                        {
                            data: 'category_id',
                            name: 'category.title'
                        },
                        {
                            data: 'brand_id',
                            name: 'brand.title'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            "className": "text-center"
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            "className": "text-center w-10"
                        },
                    ]
                });
            }
            getcatalogue();
            

            $(document).on('click', '#filterBtn', function(e) {
                e.preventDefault();  
                let category = $('#filter_form #category').val();
                let title = $('#filter_form #title').val();
                let brand = $('#filter_form #brand').val();
                
                $('#dataTable').DataTable().destroy();
                getcatalogue(category, title, brand);
            })

            $(document).on('click', '#createCatalogueBtn', function(e) {
                e.preventDefault();
                let go_next_step = true;
                if ($(this).attr('data-check-area') && $(this).attr('data-check-area').trim() !== '') {
                    go_next_step = check_validation_Form('#createModal .'+$(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    $('#createCatalogueBtn').prop('disabled', true);
                    let form = document.getElementById('createCatalogueForm');
                    var formData = new FormData(form);
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: $('#createCatalogueForm').attr('action'),
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
                            getcatalogue();
                            $('#createCatalogueBtn').prop('disabled', false);
                            $('#createModal').modal('hide');
                        },
                        error: function (xhr) {
                            $('#createCatalogueBtn').prop('disabled', false);
                            let errorMessage = '';
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                errorMessage +=(''+value+'<br>');
                            });
                            $('#createCatalogueForm .server_side_error').empty();
                            $('#createCatalogueForm .server_side_error').html('<div class="alert alert-danger" role="alert">'+errorMessage+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        },
                    })
                }
            })

            $(document).on('click', '.edit_btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                $.ajax({
                    url: "{{  url('/admin/catalogue/edit/') }}/"+id,
                    type: "GET",
                    dataType: "html",
                    success: function (data) {
                        $('#editModal .modal-content').html(data);
                        $('#edit_product_id').select2({
                            dropdownParent: $('#editModal')
                        });
                        $('#editModal').modal('show');
                        initSummerNote();
                    }
                })
            });

            $(document).on('click', '#editCatalogueBtn', function(e) {
                e.preventDefault();
                let go_next_step = true;
                if ($(this).attr('data-check-area') && $(this).attr('data-check-area').trim() !== '') {
                    go_next_step = check_validation_Form('#editModal .'+$(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    $('#editCatalogueBtn').prop('disabled', true);
                    let form = document.getElementById('editCatalogueForm');
                    var formData = new FormData(form);
                    $.ajax({
                        // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: $('#editCatalogueForm').attr('action'),
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
                            getcatalogue();
                            $('#editCatalogueBtn').prop('disabled', false);
                            $('#editModal').modal('hide');
                        },
                        error: function (xhr) {
                            $('#editCatalogueBtn').prop('disabled', false);
                            let errorMessage = '';
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                errorMessage +=(''+value+'<br>');
                            });
                            $('#editCatalogueForm .server_side_error').empty();
                            $('#editCatalogueForm .server_side_error').html('<div class="alert alert-danger" role="alert">'+errorMessage+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        },
                    })
                }
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
                            url: "{{  url('/admin/catalogue/delete/') }}/"+id,
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
                                getcatalogue();
                            }
                        })
                        
                    }
                })
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
        </script>
    @endpush
@endsection
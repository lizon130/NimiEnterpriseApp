@extends('backend.layout.app')
@section('title', 'Contact | '.Helper::getSettings('application_name') ?? 'Machine Tool Solution')
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Contact Management</h4>
        
        <div class="card my-2">
            <div class="card-header">
                <div class="row ">
                    <div class="col-12 d-flex justify-content-between">
                        <div class="d-flex align-items-center"><h5 class="m-0">Contact List</h5></div>
                        @if (Helper::hasRight('contact.create'))
                            <button type="button" class="btn btn-primary btn-create-user" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa-solid fa-plus"></i> Add</button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Toll Free</th>
                            <th>Default</th>
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
    @include('backend.pages.contact.modal')
    @push('footer')
        <script type="text/javascript">
            function getcontact(code = null, title = null, status = null){
                var table = jQuery('#dataTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('admin/contact/get/list') }}",
                        type: 'GET',
                    },
                    aLengthMenu: [
                        [25, 50, 100, 500, 5000, -1],
                        [25, 50, 100, 500, 5000, "All"]
                    ],
                    iDisplayLength: 25,
                    columns: [
                        {
                            data: 'title',
                            name: 'title'
                        },
                        {
                            data: 'phone',
                            name: 'phone'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'toll_free',
                            name: 'toll_free'
                        },
                        {
                            data: 'is_default',
                            name: 'is_default',
                            "className": "text-center w-10"
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
                            "className": "text-center w-10"
                        },
                    ]
                });
            }
            getcontact();

            $(document).on('click', '#createContactBtn', function(e) {
                e.preventDefault();
                let form = document.getElementById('createContactForm');
                var formData = new FormData(form);
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: $('#createContactForm').attr('action'),
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
                        getcontact();
                        $('#createModal').modal('hide');
                    },
                    error: function (xhr) {
                        let errorMessage = '';
                        $.each(xhr.responseJSON.errors, function(key,value) {
                            errorMessage +=(''+value+'<br>');
                        });
                        $.toast({
                            heading: 'Error',
                            text: errorMessage,
                            position: 'top-center',
                            icon: 'error'
                        })
                    },
                })
            })

            $(document).on('click', '.edit_btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                $.ajax({
                    url: "{{  url('/admin/contact/edit/') }}/"+id,
                    type: "GET",
                    dataType: "html",
                    success: function (data) {
                        $('#editModal .modal-content').html(data);
                        $('#editModal').modal('show');
                        initSummerNote();
                    }
                })
            });

            $(document).on('click', '#editContactBtn', function(e) {
                e.preventDefault();
                let form = document.getElementById('editContactForm');
                var formData = new FormData(form);
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: $('#editContactForm').attr('action'),
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
                        getcontact();
                        $('#editModal').modal('hide');
                    },
                    error: function (xhr) {
                        let errorMessage = '';
                        $.each(xhr.responseJSON.errors, function(key,value) {
                            errorMessage +=(''+value+'<br>');
                        });
                        $.toast({
                            heading: 'Error',
                            text: errorMessage,
                            position: 'top-center',
                            icon: 'error'
                        })
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
                            url: "{{  url('/admin/contact/delete/') }}/"+id,
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
                                getcontact();
                            }
                        })
                        
                    }
                })
            })
        </script>
    @endpush
@endsection
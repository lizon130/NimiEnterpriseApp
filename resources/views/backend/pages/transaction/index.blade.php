@extends('backend.layout.app')
@section('title', 'Transaction | '.Helper::getSettings('application_name') ?? 'Machine Tool Solution')
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Transaction Management</h4>

        <div class="card my-2">
            <div class="card-body pb-0">
                <form method="" id="filter_form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="date" class="form-control" name="date" id="date" placeholder="Date">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group text-start">
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
                        <div class="d-flex align-items-center"><h5 class="m-0">Transaction List</h5></div>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Transaction Date</th>
                            <th>Order Id</th>
                            <th>Details</th>
                            <th>Transaction Id</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @push('footer')
        <script type="text/javascript">
            function getorders(date = null){
                var table = jQuery('#dataTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('admin/transaction/get/list') }}",
                        type: 'GET',
                        data:{
                            'date': date,
                        },
                    },
                    aLengthMenu: [
                        [25, 50, 100, 500, 5000, -1],
                        [25, 50, 100, 500, 5000, "All"]
                    ],
                    iDisplayLength: 25,
                    "order": [
                        [ 0, 'desc' ]
                    ],
                    columns: [
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'order_id',
                            name: 'order_id'
                        },
                        {
                            data: 'order_details',
                            name: 'order_details',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'transaction_id',
                            name: 'transaction_id'
                        },
                        {
                            data: 'amount',
                            name: 'amount'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            "className": "text-center w-10"
                        }
                    ]
                });
            }
            getorders();

            $(document).on('click', '#filterBtn', function(e) {
                e.preventDefault();
                let date = $('#filter_form #date').val();
                $('#dataTable').DataTable().destroy();
                getorders(date);
            })

        </script>
    @endpush
@endsection

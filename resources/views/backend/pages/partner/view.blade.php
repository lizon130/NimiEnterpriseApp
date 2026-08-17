<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Partner Details</h5>
    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
        <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
    </button>
</div>
<div class="modal-body">
    <div class="d-flex gap-2 w-100">
        <div class="form-group w-100">
            <label>Logo</label><br>
            <img src="{{ ($partner->website_logo) ? asset('uploads/user-images/'.$partner->website_logo) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="mt-1 border" alt="">
        </div>
        <div class="form-group w-100">
            <label>Partner Type</label><br>
            {{$partner->type}}
        </div>
        <div class="form-group w-100">
            <label>Status</label><br>
            @if($partner->status == 1) <span class="badge bg-primary">Approve</span>
            @elseif($partner->status == 2) <span class="badge bg-danger">Rejected</span>
            @else <span class="badge bg-warning">Pending</span>
            @endif
        </div>
    </div>
    <hr>
    <div class="d-flex gap-2 w-100">
        <div class="form-group w-100">
            <label>First Name</label><br>
            {{$partner->user->first_name ?? ''}}
        </div>
        <div class="form-group w-100">
            <label>Last Name</label><br>
            {{$partner->user->last_name ?? ''}}
        </div>
        <div class="form-group w-100">
            <label>Company Name</label><br>
            {{$partner->name}}
        </div>
    </div>
    <div class="d-flex gap-2 w-100 mt-2">
        <div class="form-group w-100">
            <label>Department</label><br>
            {{$partner->department}}
        </div>
        <div class="form-group w-100">
            <label>VAT Reg No.</label><br>
            {{$partner->vat_no}}
        </div>
    </div>
    <div class="d-flex gap-2 w-100 mt-2">
        <div class="form-group w-100">
            <label>Email Address</label><br>
            {{$partner->email}}
        </div>
        <div class="form-group w-100">
            <label>Phone No.</label><br>
            {{$partner->phone_number}}
        </div>
    </div>
    <div class="d-flex gap-1 w-100 mt-2">
        <div class="form-group w-100">
            <label>Company Website</label><br>
            {{$partner->website_url}}
        </div>
    </div>

    <hr class="my-3">

    <h6 class="text-primary">Discount</h6>
    <div class="d-flex gap-2 w-100">
        <div class="form-group w-100">
            <label>Discount Type</label><br>
            {{$partner->discount_type}}
        </div>
        <div class="form-group w-100">
            <label>Discount</label><br>
            {{$partner->discount}}
        </div>
    </div>

    <hr class="my-3">

    <h6 class="text-primary">Address</h6>
    <div class="d-flex gap-1 w-100">
        <div class="form-group w-100">
            <label>Street Address</label><br>
            {{$partner->address}}
        </div>
    </div>
    <div class="d-flex gap-2 w-100 mt-2">
        <div class="form-group w-100">
            <label>Postal Code</label><br>
            {{$partner->post_code}}
        </div>
        <div class="form-group w-100">
            <label>City</label><br>
            {{$partner->city}}
        </div>
        <div class="form-group w-100">
            <label>State</label><br>
            {{$partner->state}}
        </div>
        <div class="form-group w-100">
            <label>Country</label><br>
            {{$partner->country}}
        </div>
    </div>
</div>
@if($partner->status == 0)
<div class="modal-footer">
    <a href="{{ route('admin.partner.status', ['id' => $partner->company_id, 'status' => 2])}}" class="btn btn-danger modal__btn_space">Reject</a>
    <a href="{{ route('admin.partner.status', ['id' => $partner->company_id, 'status' => 1])}}" class="btn btn-primary">Approve</a>
</div>
@else
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
@endif

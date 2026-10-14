<form id="editNoticeForm" action="{{ route('admin.notice.update', $notice->id)}}" method="post">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Notice</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
        </button>
    </div>
    <div class="modal-body">
        <div class="col-sm-12">
            <div class="server_side_error" role="alert"></div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-3 col-form-label">Title<span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <input type="text" name="title" class="form-control" placeholder="Notice Title" value="{{ $notice->title }}" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-3 col-form-label">Description<span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <textarea name="description" class="form-control" rows="5" placeholder="Notice details..." required>{{ $notice->description }}</textarea>
                <small class="text-muted">This notice will popup on the app home page when status is Active.</small>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-3 col-form-label">Status</label>
            <div class="col-sm-9 d-flex align-items-center">
                <div class="form-check form-switch">
                    <input class="form-check-input" @if($notice->status == 1) checked @endif type="checkbox" name="status" id="statusSwitchEdit">
                </div>
                <small class="text-muted ms-2">Active = visible on app home page, Inactive = hidden.</small>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" id="editNoticeBtn" class="btn btn-primary" data-check-area="modal-body">Update</button>
    </div>
</form>

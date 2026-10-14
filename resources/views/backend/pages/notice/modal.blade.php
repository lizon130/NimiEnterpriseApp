{{-- Create Notice Modal --}}
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="createNoticeForm" action="{{ route('admin.notice.store') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Notice</h5>
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
                            <input type="text" name="title" class="form-control" placeholder="Notice Title" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Description<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <textarea name="description" class="form-control" rows="5" placeholder="Notice details..." required></textarea>
                            <small class="text-muted">This notice will popup on the app home page when status is Active.</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-9 d-flex align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="statusSwitch" checked>
                            </div>
                            <small class="text-muted ms-2">Active = visible on app home page, Inactive = hidden.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="createNoticeBtn" class="btn btn-primary" data-check-area="modal-body">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Notice Modal --}}
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content"></div>
    </div>
</div>

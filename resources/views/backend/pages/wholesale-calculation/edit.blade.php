<form id="editForm" action="{{ route('admin.wholesale-calculation.update', $wholesale->id) }}" method="post">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Wholesale Calculation</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
        </button>
    </div>
    <div class="modal-body">
        <div class="col-sm-12">
            <div class="server_side_error" role="alert"></div>
        </div>

        <div class="form-group row mb-3">
            <label for="edit_date" class="col-sm-3 col-form-label">Date <span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <input type="date" name="date" class="form-control" id="edit_date" value="{{ $wholesale->date->format('Y-m-d') }}" required>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="edit_purchase_amount" class="col-sm-3 col-form-label">Purchase Amount <span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <div class="input-group">
                    <span class="input-group-text">৳</span>
                    <input type="number" name="purchase_amount" class="form-control" id="edit_purchase_amount" value="{{ $wholesale->purchase_amount }}" placeholder="0.00" step="0.01" min="0" required>
                </div>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="edit_sale_amount" class="col-sm-3 col-form-label">Sale Amount <span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <div class="input-group">
                    <span class="input-group-text">৳</span>
                    <input type="number" name="sale_amount" class="form-control" id="edit_sale_amount" value="{{ $wholesale->sale_amount }}" placeholder="0.00" step="0.01" min="0" required>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" id="editSubmitBtn" class="btn btn-primary">Update</button>
    </div>
</form>

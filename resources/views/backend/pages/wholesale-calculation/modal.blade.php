<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="createForm" action="{{ route('admin.wholesale-calculation.store') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Add Wholesale Calculation</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <div class="server_side_error" role="alert"></div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="date" class="col-sm-3 col-form-label">Date <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="date" name="date" class="form-control" id="date" required>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="purchase_amount" class="col-sm-3 col-form-label">Purchase Amount <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" name="purchase_amount" class="form-control" id="purchase_amount" placeholder="0.00" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="sale_amount" class="col-sm-3 col-form-label">Sale Amount <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" name="sale_amount" class="form-control" id="sale_amount" placeholder="0.00" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="createSubmitBtn" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <!-- Content will be loaded via AJAX -->
        </div>
    </div>
</div>

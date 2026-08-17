{{-- Create Partner Modal --}}
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="partnerCreateForm" action="{{ route('admin.partner.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Partner</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="server_side_error" role="alert"></div>
                        </div>
                        <div class="col-sm-12">
                            <!-- Basic Information -->
                            <h6 class="mb-3 text-primary">Basic Information</h6>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>Partner Type <span class="text-danger">*</span></label>
                                    <select name="type" class="form-control" required>
                                        <option value="">Select Type</option>
                                        <option value="Reseller">Reseller</option>
                                        <option value="Distributor">Distributor</option>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="0">Pending</option>
                                        <option value="1">Approved</option>
                                        <option value="2">Rejected</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control" required>
                                </div>
                                <div class="col-lg-6">
                                    <label>Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>Shop Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="col-lg-6">
                                    <label>Address <span class="text-danger">*</span></label>
                                <textarea name="address" class="form-control" rows="2" required></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control">
                                </div>
                                <div class="col-lg-6">
                                    <label>Phone No.</label>
                                    <input type="text" name="phone_no" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                                <div class="col-lg-6">
                                    <label>Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Company Logo</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <small class="text-muted">Allowed: png, jpg, jpeg, gif, webp</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="addPartnerBtn" class="btn btn-primary">Add Partner</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Partner Modal --}}
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"></div>
    </div>
</div>

{{-- View Partner Modal --}}
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"></div>
    </div>
</div>

{{-- Assign Product Modal --}}
<div class="modal fade" id="assignProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form id="assignProductForm" action="{{ route('admin.partner.product.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Assign Product to Partner</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="server_side_error" role="alert"></div>
                        </div>

                        <div class="col-lg-6 form-group">
                            <label>Company <span class="text-danger">*</span></label>
                            <select name="company" class="form-control company_select" required>
                                <option value="">Select Company</option>
                                @isset($partners)
                                    @foreach ($partners as $partner)
                                        <option value="{{ $partner->company_id }}">{{ $partner->name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>

                        <div class="col-lg-6 form-group">
                            <label>Partner Name <span class="text-danger">*</span></label>
                            <input type="text" name="partner" class="form-control partner_name" placeholder="Partner Name" readonly>
                        </div>

                        <div class="col-lg-12 form-group products_area">
                            <table class="w-100" id="productsTable">
                                <thead>
                                    <tr>
                                        <th width="40%">Product</th>
                                        <th width="50%">Details</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="productsBody">
                                    <tr class="product-row" data-row="1">
                                        <td>
                                            <select name="category[]" class="form-control category-select mb-1" data-row="1">
                                                <option value="">Select Category</option>
                                                @isset($category)
                                                    @foreach ($category as $cat)
                                                        <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                                                    @endforeach
                                                @endisset
                                            </select>
                                            <select name="product[]" class="form-control product-select" data-row="1" style="width:100%;">
                                                <option value="">Select Product</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <input type="number" min="0" class="form-control" name="quantity[]" placeholder="Quantity">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="price[]" placeholder="Price">
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <select name="discount_type[]" class="form-control">
                                                                <option value="">Discount Type</option>
                                                                <option value="percent">Percent</option>
                                                                <option value="amount">Amount</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" name="discount[]" class="form-control" placeholder="Discount">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" data-row="1" class="btn btn-sm btn-danger remove-product-btn"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3">
                                            <button type="button" class="btn btn-sm btn-primary" id="addProductBtn">
                                                <i class="fa-solid fa-plus"></i> Add More Product
                                            </button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Assign Products</button>
                </div>
            </form>
        </div>
    </div>
</div>

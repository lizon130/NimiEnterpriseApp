<!-- Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="createCategoryForm" action="{{ route('admin.brand.store') }}" method="post" enctype="multipart/form-data">
                @csrf 
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Brand</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <div class="server_side_error" role="alert">

                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Brand Name<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="title" class="form-control" placeholder="Brand Name" required>
                        </div>
                    </div>
                    
                    <div class="form-group  row">
                        <label for="" class="col-sm-3 col-form-label">Visibility</label>
                        <div class="col-sm-3 d-flex align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="flexSwitchCheckDefault">
                            </div>
                        </div>
                        <label for="" class="col-sm-3 col-form-label">Show Home</label>
                        <div class="col-sm-3 d-flex align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="show_home" >
                            </div>
                        </div>
                    </div>

                    <div class="form-group  row">
                        <label for="" class="col-sm-3 col-form-label">Image</label>
                        <div class="col-sm-9">
                            <input type="file" class="form-control" onchange="previewFile('createModal #category_image', 'createModal .preview_image')" name="image" id="category_image">

                            <img src="{{asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="preview_image mt-1 border" alt="">
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <a type="button" class="modal__btn_space" data-bs-dismiss="modal">Close</a>
                    <button type="submit" id="createCategoryBtn" class="btn btn-primary" data-check-area="modal-body">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- edit modal  --}}
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            
        </div>
    </div>
</div>

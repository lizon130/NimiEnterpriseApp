<form id="editCategoryForm" action="{{ route('admin.brand.update', $single_category->id)}}" method="post" enctype="multipart/form-data">
    @csrf 
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Brand</h5>
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
            <div class="col-sm-8">
                <input type="text" name="title" class="form-control" placeholder="Brand Name" value="{{ $single_category->getTranslation(Session::get('admin_language') ?? 'en', 'title') ?? $single_category->title }}" required>
            </div>
            
        </div>
        
        
        <div class="form-group  row">
            <label for="" class="col-sm-3 col-form-label">Visibility</label>
            <div class="col-sm-3 d-flex align-items-center">
                <div class="form-check form-switch">
                    <input class="form-check-input" @if($single_category->status == 1) checked @endif type="checkbox" name="status" id="flexSwitchCheckDefault">
                </div>
            </div>
            <label for="" class="col-sm-3 col-form-label">Show Home</label>
            <div class="col-sm-3 d-flex align-items-center">
                <div class="form-check form-switch">
                    <input class="form-check-input" @if($single_category->show_home == 1) checked @endif type="checkbox" name="show_home" >
                </div>
            </div>
        </div>

        <div class="form-group  row">
            <label for="" class="col-sm-3 col-form-label">Image</label>
            <div class="col-sm-9">
                <input type="file" class="form-control" onchange="previewFile('editModal #category_image', 'editModal .preview_image')" name="image" id="category_image">

                <img src="{{ ($single_category->image) ? asset('uploads/brand-images/'.$single_category->image) :  asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="preview_image mt-1 border" alt="">
            </div>
            
        </div>
    </div>
    <div class="modal-footer">
        <a type="button" class="modal__btn_space" data-bs-dismiss="modal">Close</a>
        <button type="submit" id="editCategoryBtn" class="btn btn-primary" data-check-area="modal-body">Update</button>
    </div>
</form>
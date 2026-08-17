<form id="editCategoryForm" action="{{ route('admin.category.update', $single_category->id)}}" method="post" enctype="multipart/form-data">
    @csrf 
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
        </button>
    </div>
    <div class="modal-body">
        <div class="col-sm-12">
            <div class="server_side_error" role="alert">

            </div>
        </div>
        <div class="form-group  row">
            <label for="" class="col-sm-3 col-form-label">Parent Category</label>
            <div class="col-sm-9">
                <select name="parent_category" class="form-control edit_parent_category" style="width: 100%">
                    <option value="">Select</option>
                    @foreach ($parent_category as $category)
                        <option @if($single_category->parent_category == $category->id) selected @endif value="{{ $category->id}}">{{ $category->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-3 col-form-label">Categoy Name<span class="text-danger">*</span></label>
            <div class="col-sm-7">
                <input type="text" name="title" class="form-control" placeholder="Category Name" value="{{ $single_category->getTranslation(Session::get('admin_language') ?? 'en', 'title') ?? $single_category->title }}" required>
            </div>
            <div class="col-sm-2 d-flex align-items-center">
                <label for="" class="d-flex align-items-center"> <input type="checkbox" name="is_parent" @if($single_category->is_parent == 1) checked @endif > Is Parent?</label>
            </div>
        </div>
        
        {{-- <div class="form-group row">
            <label for="" class="col-sm-3 col-form-label">Alternative Name</label>
            <div class="col-sm-9">
                <input type="text" name="alternate_name" class="form-control" value="{{$single_category->alternate_name }}" placeholder="Alternative Name">
            </div>
        </div>

        <div class="form-group row">
            <label for="" class="col-sm-3 col-form-label">Alternative Value</label>
            <div class="col-sm-9">
                <input type="text" name="value" class="form-control" value="{{$single_category->value }}" placeholder="Alternative Value">
            </div>
        </div> --}}
        
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
                <input type="file" class="form-control" onchange="previewFile('createModal #category_image', 'createModal .preview_image')" name="image" id="category_image">

                <img src="{{ ($single_category->image) ? asset('uploads/category-images/'.$single_category->image) :  asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="preview_image mt-1 border" alt="">
            </div>
        </div>
		
		<div class="form-group row">
			<label for="" class="col-sm-3 col-form-label">Order Number</label>
			<div class="col-sm-9">
				<input type="number" min="0" name="short_number" class="form-control" value="{{ $single_category->short_number}}" placeholder="Order Number" >
			</div>
		</div>
		
    </div>
    <div class="modal-footer">
        <a type="button" class="modal__btn_space" data-bs-dismiss="modal">Close</a>
        <button type="submit" id="editCategoryBtn" class="btn btn-primary" data-check-area="modal-body">Update</button>
    </div>
</form>
<!-- Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="createCategoryForm" action="{{ route('admin.category.store') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <div class="server_side_error" role="alert">

                        </div>
                    </div>
                    {{-- <div class="form-group  row">
                        <label for="" class="col-sm-3 col-form-label">Parent Category</label>
                        <div class="col-sm-9">
                            <select name="parent_category" class="form-control parrent_category" style="width: 100%">
                                <option value="">Select</option>
                                @foreach ($parent_category as $category)
                                    <option value="{{ $category->id}}">{{ $category->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Categoy Name<span
                                class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <input type="text" name="title" class="form-control" placeholder="Category Name"
                                required>
                        </div>
                        <div class="col-sm-2 d-flex align-items-center">
                            <label for="" class="d-flex align-items-center"><input type="checkbox"
                                    name="is_parent"> Is Parent?</label>
                        </div>
                    </div>

                    {{-- <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Alternative Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="alternate_name" class="form-control" placeholder="Alternative Name">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Alternative Value</label>
                        <div class="col-sm-9">
                            <input type="text" name="value" class="form-control" placeholder="Alternative Value">
                        </div>
                    </div> --}}

                    <div class="form-group  row">
                        <label for="" class="col-sm-3 col-form-label">Visibility</label>
                        <div class="col-sm-3 d-flex align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status"
                                    id="flexSwitchCheckDefault">
                            </div>
                        </div>
                        <label for="" class="col-sm-3 col-form-label">Show Home</label>
                        <div class="col-sm-3 d-flex align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="show_home">
                            </div>
                        </div>
                    </div>

                    <div class="form-group  row">
                        <label for="" class="col-sm-3 col-form-label">Image</label>
                        <div class="col-sm-3">
                            <input type="file" class="form-control"
                                onchange="previewFile('createModal #category_image', 'createModal .preview_image')"
                                name="image" id="category_image">

                            <img src="{{ asset('assets/img/no-img.jpg') }}" height="80px" width="100px"
                                class="preview_image mt-1 border" alt="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Order Number</label>
                        <div class="col-sm-9">
                            <input type="number" min="0" name="short_number" class="form-control"
                                placeholder="Order Number">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a type="button" class="modal__btn_space" data-bs-dismiss="modal">Close</a>
                    <button type="submit" id="createCategoryBtn" class="btn btn-primary"
                        data-check-area="modal-body">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- edit modal  --}}
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="createCatalogueForm" action="{{ route('admin.catalogue.store') }}" method="post" enctype="multipart/form-data">
                @csrf 
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Catalogue</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="server_side_error" role="alert">
    
                            </div>
                        </div>
                        <div class="col-sm-12 tab-content" id="v-pills-tabContent">
                            <div class="step step_1 tab-pane fade show active">
							
								<div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">Type<span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="type">
											<option value="catalogue">Catalogue</option>
											<option value="manual">Manual</option>
											<option value="form">Form</option>
										</select>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">Catalogue Title<span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="title" class="form-control" placeholder="Catalogue Title" required>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">Category</label>
                                    <div class="col-sm-9">
                                        <select name="category_id" class="form-control" >
                                            <option value="">Select</option>
                                            @foreach ($categorys as $item)
                                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">Brand</label>
                                    <div class="col-sm-9">
                                        <select name="brand_id" class="form-control" >
                                            <option value="">Select</option>
                                            @foreach ($brands as $item)
                                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">Product</label>
                                    <div class="col-sm-9">
                                        <select name="product_id" class=" form-control" id="product_id" style="width:100%;">
                                            <option value="">Select</option>
                                            @foreach ($products as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">Short Description</label>
                                    <div class="col-sm-9">
                                        <textarea name="short_description" class="form-control"  cols="30" rows="5" required></textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="step step_2 tab-pane fade" >

                                <div class="form-group  row">
                                    <label for="" class="col-sm-3 col-form-label">Image<span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" accept="image/*" onchange="previewFile('createModal #catalogue_image', 'createModal .preview_image')" name="image" id="catalogue_image" required>
                                        <img src="{{asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="preview_image mt-1 border" alt="">
                                    </div>
                                </div>

                                <div class="form-group  row">
                                    <label for="" class="col-sm-3 col-form-label">File<span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" accept="application/pdf" onchange="previewFile('createModal #catalogue_file', 'createModal .preview_file')" name="file" id="catalogue_file" required>
                                        <img src="{{asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="preview_file mt-1 border" alt="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">Visibility</label>
                                    <div class="col-sm-9">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status" id="flexSwitchCheckDefault">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-block step_btn step_btn_1">
                        <button type="button" data-step-open="step_2" data-step-button="step_btn_2" class="btn btn-primary next_btn" data-check-area="step_1">Next</button>
                    </div>
                    <div class="d-none step_btn step_btn_2">
                        <a type="button" class="btn m-pr-btn modal__btn_space next_btn" data-step-open="step_1" data-step-button="step_btn_1">Previous</a>
                        <button type="submit" id="createCatalogueBtn" class="btn btn-primary" data-check-area="step_2">Add</button>
                    </div>
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

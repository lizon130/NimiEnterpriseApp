<form id="editCatalogueForm" action="{{ route('admin.catalogue.update', $catalogue->id)}}" method="post" enctype="multipart/form-data">
    @csrf 
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Catalogue</h5>
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
								<option @if($catalogue->type == 'catalogue') selected @endif value="catalogue">Catalogue</option>
								<option @if($catalogue->type == 'manual') selected @endif value="manual">Manual</option>
								<option @if($catalogue->type == 'form') selected @endif value="form">Form</option>
							</select>
						</div>
					</div>
                    
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Catalogue Title<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="title" class="form-control" value="{{ $catalogue->title }}" placeholder="Catalogue Title" required>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Category</label>
                        <div class="col-sm-9">
                            <select name="category_id" class="form-control" >
                                <option value="">Select</option>
                                @foreach ($categorys as $item)
                                    <option @if($item->id == $catalogue->category_id) selected @endif value="{{ $item->id }}">{{ $item->title }}</option>
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
                                    <option @if($item->id == $catalogue->brand_id) selected @endif value="{{ $item->id }}">{{ $item->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Product</label>
                        <div class="col-sm-9">
                            <select name="product_id" class=" form-control" id="edit_product_id" style="width:100%;">
                                <option value="">Select</option>
                                @foreach ($products as $item)
                                    <option @if($item->id == $catalogue->product_id) selected @endif value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Short Description</label>
                        <div class="col-sm-9">
                            <textarea name="short_description" class="form-control"  cols="30" rows="5" required>{{$catalogue->short_description}}</textarea>
                        </div>
                    </div>

                </div>
                <div class="step step_2 tab-pane fade" >

                    <div class="form-group  row">
                        <label for="" class="col-sm-3 col-form-label">Image</label>
                        <div class="col-sm-9">
                            <input type="file" class="form-control" accept="image/*" onchange="previewFile('editModal #catalogue_image', 'createModal .preview_image')" name="image" id="catalogue_image">
                            <img src="{{ ($catalogue->thumbnail) ? asset('uploads/catalogue-images/'.$catalogue->thumbnail) :  asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="preview_image mt-1 border" alt="">
                        </div>
                    </div>

                    <div class="form-group  row">
                        <label for="" class="col-sm-3 col-form-label">File</label>
                        <div class="col-sm-9">
                            <input type="file" class="form-control mb-4" accept="application/pdf" onchange="previewFile('editModal #catalogue_file', 'createModal .preview_file')" name="file" id="catalogue_file">
                            <div class="d-flex">  
                                @foreach (App\Models\Translation::where('translatable_type', App\Models\Catalogue::class)->where('translatable_id', $catalogue->id)->where('field', 'file')->get() as $files)
                                    {{-- <a target="_blank" href="{{ asset('uploads/catalogue-files/'.$catalogue->file) }}">
                                        <span class="text-uppercase">{{ $files->language_code }}</span>
                                    </a>  --}}
                                    <p class="w-10 position-relative p-2 border me-2">
                                        <a target="_blank" class="text-uppercase" href="{{ asset('uploads/catalogue-files/'.$files->file) }}">{{ $files->language_code }}</a>
                                        <a href="{{ route('admin.catalogue.local.delete', $files->id) }}" class="position-absolute top-0 end-0 text-danger"><i class="fa-solid fa-circle-xmark"></i></a>
                                    </p>
                                @endforeach
                            </div>  
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Visibility</label>
                        <div class="col-sm-9">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" @if($catalogue->status == 1) checked @endif name="status" id="flexSwitchCheckDefault">
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
            <button type="submit" id="editCatalogueBtn" class="btn btn-primary" data-check-area="step_2">Update</button>
        </div>
    </div>
</form>
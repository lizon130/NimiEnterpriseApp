<form id="editNewsForm" action="{{ route('admin.news.update', $news->id)}}" method="post" enctype="multipart/form-data">
    @csrf 
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit News</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12 tab-content" id="v-pills-tabContent">
                <div class="step step_1 tab-pane fade show active">
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Publish Date<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="date" name="publish_date" value="{{$news->publish_date}}" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">News Title<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="title" class="form-control" value="{{ $news->getTranslation(Session::get('admin_language') ?? 'en', 'title') ?? '' }}" placeholder="News Title" required>
                        </div>
                    </div>
					
					<div class="form-group row">
						<label for="" class="col-sm-3 col-form-label">Category<span class="text-danger">*</span></label>
						<div class="col-sm-9">
							<select name="category" class="form-control" required>
								<option value="">-- Select --</option>
								<option value="News" @if($news->category == 'News') selected @endif>News</option>
								<option value="Media" @if($news->category == 'Media') selected @endif>Media</option>
								<option value="Promotions" @if($news->category == 'Promotions') selected @endif>Promotions</option>
								<option value="Show" @if($news->category == 'Show') selected @endif>Show</option>
								<option value="Uncategorized" @if($news->category == 'Uncategorized') selected @endif>Uncategorized</option>
							</select>
						</div>
					</div>
                    
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Short Description</label>
                        <div class="col-sm-9">
                            <textarea name="short_description" class="form-control" id="" cols="30" rows="8" required>{{ $news->getTranslation(Session::get('admin_language') ?? 'en', 'short_description') ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">URL</label>
                        <div class="col-sm-9">
                            <input type="text" name="url" class="form-control" placeholder="News Url" value="{{$news->url}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Visibility</label>
                        <div class="col-sm-9">
                            <div class="form-check form-switch">
                                <input class="form-check-input" @if($news->status == 1) checked @endif type="checkbox" name="status" id="flexSwitchCheckDefault">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="step step_2 tab-pane fade" >
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Description</label>
                        <div class="col-sm-9">
                            <textarea id="edit_descriptions" class="tinymceText form-control" >{!! $news->getTranslation(Session::get('admin_language') ?? 'en', 'description') ?? '' !!}</textarea>
                        </div>
                    </div>

                    <div class="form-group  row">
                        <label for="" class="col-sm-3 col-form-label">Image</label>
                        <div class="col-sm-9">
                            <input type="file" class="form-control" onchange="previewFile('editModal #news_image', 'editModal .preview_image')" name="image" id="news_image" >
                            <img src="{{ ($news->media) ? asset('uploads/news-images/'.$news->media) :  asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="preview_image mt-1 border" alt="">
                        </div>
                    </div>
					
					<div class="form-group  row">
                        <label for="" class="col-sm-3 col-form-label">Gallery Images</label>
                        <div class="col-sm-9">
                            <input type="file" multiple class="form-control" onchange="previewFile('editModal #news_gallery_image', 'editModal .preview_gallery_image')" name="gallery_image[]" id="news_gallery_image" >
                            <img src="{{ asset('assets/img/no-img.jpg') }}" height="80px" width="100px" class="preview_gallery_image mt-1 border" alt="">
                        </div>
                    </div>
					
					<div class="form-group  row">
						<label for="" class="col-sm-3 col-form-label">File</label>
						<div class="col-sm-9">
							<input type="file" class="form-control" name="file" id="" >
							<a target="_blank" href="{{ asset('uploads/news-files/'.$news->file) }}">{{ $news->file }}</a>
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
            <button type="submit" id="editNewsBtn" class="btn btn-primary" data-check-area="step_2">Update</button>
        </div>
    </div>
</form>
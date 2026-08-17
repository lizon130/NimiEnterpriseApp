@extends('frontend.layout.app')
@section('content')
	<div class="modal fade" id="image_zoom_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog modal-fullscreen">
			<div class="modal-content">
				<div class="modal-header border-0">
					<h5 class="modal-title" id="staticBackdropLabel"></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body ">
					<div>
						<div class="owl-carousel owl-theme" id="image_zoom_modal_slider">
							<div class="item d-flex justify-content-center">
								<img class="zoom_image" src="{{ asset('uploads/news-images/' . $news->media) }}" height="100%" width="100%"
									alt="">
							</div>
							@if ($news->gallery_images != null && count($news->gallery_images) > 0)
								@foreach ($news->gallery_images as $image)
									<div class="item d-flex justify-content-center">
										<img class="zoom_image" src="{{ asset('uploads/news-images/' . $image) }}" height="100%" width="100%" alt="">
									</div>
								@endforeach
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <div id="newsDetails">
        <div class="breadcrumb__nk">
            <div class="container"><a href="{{ route('home') }}" class="text-light">{{ trans('language.home') }} </a> / <a href="{{ route('news') }}" class="text-light">{{ trans('language.news') }}</a> / {{ $news->getTranslation(Session::get('language') ?? 'en', 'title') ?? $news->title }}</div>
        </div>
		<!--
        <div class="container go_back_container">
            <a href="{{ URL::previous() }}"><i class="fa-solid fa-angle-left"></i> {{ trans('language.go_back') }}</a>
        </div>-->
        <div class="newsDetails__container container">
            <h1 class="newsDetails__container--title">{{ $news->getTranslation(Session::get('language') ?? 'en', 'title') ?? $news->title }}</h1>
            <span class="newsDetails__container--time">{{ date('d F, Y', strtotime($news->publish_date)) }}</span>
            <div class="newsDetails__container--topImage">
				<div class="enlarged_image_container">
					<img id="enlarged_image" class="enlarged_image mx-auto img-fluid" src="{{ asset('uploads/news-images/'.$news->media) }}"
						alt="">
				</div>
				<div class="">
					<div class="owl-carousel owl-theme" id="news_image_slider">
						<div class="item">
							<img src="{{ asset('uploads/news-images/' . $news->media) }}" alt="" class="small-img">
						</div>
						@if ($news->gallery_images != null && count($news->gallery_images) > 0)
							@foreach ($news->gallery_images as $image)
								<div class="item"><img src="{{ asset('uploads/news-images/' . $image) }}" alt="" class="small-img">
								</div>
							@endforeach
						@endif
					</div>
				</div>
            </div>
            <p class="newsDetails__container--description">
                {!! $news->getTranslation(Session::get('language') ?? 'en', 'description') ?? $news->description !!}
            </p>
            <div class="newsDetails__container--nextPrev">
                <a href="{{ route('news.details', App\Models\News::where('status',1)->where('id', '<', $news->id)->orderBy('id', 'desc')->value('id') ?? 1) }}" @if(!App\Models\News::where('status',1)->where('id', '<', $news->id)->orderBy('id', 'desc')->value('id')) disabled="" @endif class="btn">{{ trans('language.btn_previous') }}</a>
                <a href="{{ route('news.details', App\Models\News::where('status',1)->where('id', '>', $news->id)->orderBy('id', 'asc')->value('id') ?? 1) }}" @if(!App\Models\News::where('status',1)->where('id', '<', $news->id)->orderBy('id', 'desc')->value('id')) disabled="" @endif class="btn">{{ trans('language.btn_next') }}</a>
            </div>
        </div>
    </div>
	
	
	@push('footer')
	    <script>
			$(document).ready(function() {
				$('#news_image_slider').owlCarousel({
					loop: false,
					margin: 10,
					nav: true,
					dots: true,
					autoplay:true,
					responsive: {
						0: {
							items: 2
						},
						600: {
							items: 3
						},
						1000: {
							items: 4
						}
					}
				});
				
				$('.small-img').click(function() {
					var src = $(this).attr('src');
					$('#enlarged_image').attr('src', src);
				})
				
				$('#image_zoom_modal_slider').owlCarousel({
					loop: false,
					margin: 10,
					nav: true,
					dots: false,
					autoplay:false,
					responsive: {
						0: {
							items: 1
						},
						600: {
							items: 1
						},
						1000: {
							items: 1
						}
					}
				});
				
				$('.enlarged_image').click(function() {
					//let main_image = $(this).attr('src');
					//$('#image_zoom_modal .zoom_image').attr('src', main_image);
					$('#image_zoom_modal').modal('show');
				});
			})
		</script>
	@endpush
@endsection
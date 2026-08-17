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
							<img class="zoom_image"  src="{{ asset('uploads/part-images/'.$part->thumbnail) }}" height="100%" width="100%"
								alt="">
						</div>
						@if ($part->images != null && count($part->images) > 0)
							@foreach ($part->images as $image)
								<div class="item d-flex justify-content-center">
									<img class="zoom_image" src="{{ asset('uploads/part-images/' . $image) }}" height="100%" width="100%" alt="">
								</div>
							@endforeach
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="productDetails">
    <div class="breadcrumb__nk">
        <div class="container"><a href="{{ route('home') }}" class="text-light">{{ trans('language.home') }} </a> / <a href="{{ route('parts')}}" class="text-light">{{ trans('language.product_parts') }}</a>  / {{ $part->getTranslation(Session::get('language') ?? 'en', 'title') ?? $part->name }}</div>
    </div>
	<!--
    <div class="container go_back_container">
        <a href="{{ URL::previous() }}"><i class="fa-solid fa-angle-left"></i> {{ trans('language.go_back') }}</a>
    </div>-->
    <div class="productDetails__container container">
        <div class="productDetails_img_wrapper" style="overflow: hidden;">
            <div class="enlarged_image_container">
                <img class="enlarged_image mx-auto" src="{{ asset('uploads/part-images/'.$part->thumbnail) }}" alt="">
            </div>
            <div class="slider productDetails_slider-1">
                <div class="carousel-1 owl-carousel owl-theme">
					@if ($part->images != null && count($part->images) > 0)
						@if($part->images != null)
							@foreach ($part->images as $image)
								<div class="item"><img src="{{ asset('uploads/part-images/'.$image) }}" alt=""></div>
							@endforeach
						@endif
					@endif
                    <div class="item"><img src="{{ asset('uploads/part-images/'.$part->thumbnail) }}" alt=""></div>
                </div>
            </div>
        </div>
        <div class="mt-2">
            <h3 class="productDetails_title">{{ $part->getTranslation(Session::get('language') ?? 'en', 'name') ?? $part->name }}</h3>
            <p class="productDetails_code">{{ trans('language.product_code') }} - {{ $part->code }}</p>
			<hr>
            <div class="productDetails__price__order">
                <div class="price__section">
                    @if (auth()->check() && $part->price > 0)
                        <h5 class="price">Price: ${{ Helper::partPriceFaterOffer($part->id) }}</h5>
                        @if ($part->discount > 0)
                            <p class="price__discount"> {{ trans('language.old_price') }}:
                                @if(Helper::partPriceFaterOffer($part->id) < $part->price )
                                    <del>${{ $part->price }}</del>
                                @endif
                            </p>
                        @endif
                    @endif
                </div>
				
                <div class="order__btn__area">
                    @if (auth()->check() && $part->price > 0)
                    <a href="{{ route('add.to.cart', ['type' => 'part', 'id' => $part->id])}}" class="btn order__btn add-to-cart">{{ trans('language.btn_order') }}</a>
                        <a href="{{ route('add.to.wishlist', ['type' => 'part', 'id' => $part->id])}}" class="btn wishlist__btn add-to-wishlist"><i class="fa-solid fa-heart"></i></a>
                    @else
                        <a href="{{ route('add.to.inquiry', $part->id) }}" class="btn inquiry__btn">{{ trans('language.btn_add_to_inquiry_list') }}</a>
                        <a href="{{ route('add.to.wishlist', ['type' => 'part', 'id' => $part->id]) }}" class="btn wishlist__btn add-to-wishlist"><i class="fa-solid fa-heart"></i></a>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
</div>
<div class=" mt-4">
    <div class="col-lg-12 p-0">
        <ul class="product__details__nav nav nav-pills justify-content-center" id="pills-tab" role="tablist">
            @foreach($custom_fields as $field)
                @if ($field->field_name != 'Product Information')
                    @if ($part->custom_options && count($part->custom_options()->where('custom_field_id', $field->id)->get()) > 0)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if($loop->iteration == 1) active @endif" id="pills-{{ $field->id }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $field->id }}" type="button" role="tab" aria-controls="pills-{{ $field->id }}" aria-selected="true">{{ $field->field_name }}</button>
                        </li>
                    @endif
                @else
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($loop->iteration == 1) active @endif" id="pills-{{ $field->id }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $field->id }}" type="button" role="tab" aria-controls="pills-{{ $field->id }}" aria-selected="true">Part Information</button>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>
<div class="product__details-content pt-4 pb-5">
    <div class="tab-content container" id="pills-tabContent">
        @foreach($custom_fields as $field)
            <div class="tab-pane fade @if($loop->iteration == 1) show active @endif" id="pills-{{ $field->id }}" role="tabpanel" aria-labelledby="pills-{{ $field->id }}-tab">
                @if ($field->field_name == 'Product Information')
                    <div class="row ">
                        <div class="col-lg-12 productDetails__keyFeatures">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h3 class="productDetails__keyFeatures--title ">{{ trans('language.Key_data_at_a_glance') }}</h3>
                                    {!! $part->getTranslation(Session::get('language') ?? 'en', 'key_features') ?? $part->key_features !!}
									
									<h3 class="productDetails__keyFeatures--title">{{ trans('Further Information') }}</h3>
									{!! $part->getTranslation(Session::get('language') ?? 'en', 'further_information') ?? $part->further_information !!}
									
                                    <div class="product-attributes__data-list">
                                        @foreach ($part->attributes as $attribute)
                                            @if ($attribute->attribute_name != null)
                                                <div class="data__item">
                                                    <div class="col-1">{{ $attribute->attribute_name }}</div>
                                                    <div class="col-2">{{ $attribute->value }}</div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                    @foreach(\App\Models\PartAttribute::select('sub_option', DB::raw('MAX(id) as id'))
                                    ->where('type', 'custom value')
                                    ->where('custom_field_id', $field->id)
                                    ->where('part_id', $part->id)
                                    ->where('ancestor_id', null)
                                    ->groupBy('sub_option')
                                    ->get() as $row)
                                        
                                        
                                        @if ($row->sub_option == 'More Options')
                                            <h5 class="productDetails__keyFeatures--title mt-4">{{ $row->sub_option }}:</h5>
                                            <div class="list--product-features row m-0">
                                                @foreach(\App\Models\PartAttribute::where('part_id', $part->id)->where('type', 'custom value')->where('custom_field_id', $field->id)->where('language_code', Session::get('admin_language') ?? 'en')->where('ancestor_id', $row->id)->get() as $option)
                                                    <div class="col-lg-4 mb-4">
                                                        <div class="list__item svg">
                                                            <img src="{{ asset('uploads/product-custom-files/' . $option->image) }}" class="icon" alt="">
                                                            <h6 class="heading">{{ $option->title }}</h6>
                                                            <p class="text">{{ $option->value }}</p>   
                                                            <p class="zusatztext"> {{ $option->details }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach 
                                            </div>
                                        @elseif($row->sub_option == 'Single components features')
                                            <h5 class="productDetails__keyFeatures--title mt-4">{{ $row->sub_option }}:</h5>
                                            <div class="product_detail_accordion accordion w-100" id="accordionExample">
                                                @foreach(\App\Models\PartAttribute::select('title')
                                                ->where('type', 'custom value')
                                                ->where('custom_field_id', $field->id)
                                                ->where('part_id', $part->id)
                                                ->where('language_code', Session::get('admin_language') ?? 'en')
                                                ->where('ancestor_id', $row->id)
                                                ->whereNull('value')
                                                ->whereNull('details')
                                                ->groupBy('title')
                                                ->get() as $sfeatures)
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingOne{{$loop->iteration}}">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne{{$loop->iteration}}" aria-expanded="false" aria-controls="collapseOne{{$loop->iteration}}">
                                                            {{ $sfeatures->title }}
                                                            </button>
                                                        </h2>
                                                        <div id="collapseOne{{$loop->iteration}}" class="accordion-collapse collapse" aria-labelledby="headingOne{{$loop->iteration}}" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body ps-0">
                                                                <div class="product-attributes__data-list">
                                                                    @foreach(\App\Models\PartAttribute::where('type', 'custom value')
                                                                    ->where('custom_field_id', $field->id)
                                                                    ->where('part_id', $part->id)
                                                                    ->where('language_code', Session::get('admin_language') ?? 'en')
                                                                    ->where('ancestor_id', $row->id)
                                                                    ->where('title', $sfeatures->title)
                                                                    ->get() as $sfeatureoption)
                                                                        @if ($sfeatureoption->title != null && $sfeatureoption->value != null)
                                                                            <div class="data__item">
                                                                                <div class="col-1">{{ $sfeatureoption->value }}</div>
                                                                                <div class="col-2">{{ $sfeatureoption->details }}</div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif($row->sub_option == 'Notice')
                                            <div class="row m-0">
                                                <div class="col-lg-4 ps-0">
                                                    <h5 class="productDetails__keyFeatures--title ">{{ $row->sub_option }}:</h5>
                                                </div>
                                                <div class="col-lg-8">
                                                    <p>{{ \App\Models\PartAttribute::where('part_id', $part->id)->where('type', 'custom value')->where('custom_field_id', $field->id)->where('language_code', Session::get('admin_language') ?? 'en')->where('ancestor_id', $row->id)->where('sub_option', 'Notice')->whereNull('title')->first()->details ?? '' }}</p>
                                                </div>
                                            </div>
                                        @elseif($row->sub_option == 'Scope of delivery')
                                            <div class="row m-0">
                                                <div class="col-lg-4 ps-0">
                                                    <h5 class="productDetails__keyFeatures--title ">{{ $row->sub_option }}:</h5>
                                                </div>
                                                <div class="col-lg-8">
                                                    <p>{{ \App\Models\PartAttribute::where('part_id', $part->id)->where('type', 'custom value')->where('custom_field_id', $field->id)->where('language_code', Session::get('admin_language') ?? 'en')->where('ancestor_id', $row->id)->where('sub_option', 'Scope of delivery')->whereNull('title')->first()->details ?? '' }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="col-lg-4">
                                    <img class="enlarged_image mx-auto" width="400px" src="{{ asset('uploads/part-images/' . $part->thumbnail) }}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($field->field_name == 'Benefits')
                    @foreach(\App\Models\PartAttribute::select('sub_option', DB::raw('MAX(id) as id'))
                    ->where('type', 'custom value')
                    ->where('custom_field_id', $field->id)
                    ->where('part_id', $part->id)
                    ->where('ancestor_id', null)
                    ->groupBy('sub_option')
                    ->get() as $row)
                        <div class="row mt-4">
                            <div class="col-lg-12 text-center">
                                <h3 class="productDetails__keyFeatures--title mb-3">{{ $row->sub_option }}</h3>
                                <p>{{ \App\Models\PartAttribute::where('type', 'custom value')
                                    ->where('custom_field_id', $field->id)
                                    ->where('part_id', $part->id)
                                    ->where('ancestor_id', $row->id)
                                    ->whereNull('title')
                                    ->first()->details ?? '' }}</p>
                            </div>
                            <div class="col-lg-12 p-0 mt-4">
                                <div class="row">
                                    @foreach (\App\Models\PartAttribute::where('type', 'custom value')
                                    ->where('custom_field_id', $field->id)
                                    ->where('part_id', $part->id)
                                    ->where('ancestor_id', $row->id)
                                    ->whereNotNull('title')
                                    ->get() as $benifit)
                                        <div class="col-lg-6 mb-4">
                                            <div class="benifit-list-item d-flex">
                                                <div class="icon">
                                                    <i class="fa-solid fa-arrow-right"></i>
                                                </div>
                                                <div>
                                                    <strong class="title">{{ $benifit->title }}</strong>
                                                    <span class="details">{{ $benifit->details }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                @elseif ($field->field_name == 'Application')
                    @foreach(\App\Models\PartAttribute::select('sub_option', DB::raw('MAX(id) as id'))
                    ->where('type', 'custom value')
                    ->where('custom_field_id', $field->id)
                    ->where('part_id', $part->id)
                    ->where('ancestor_id', null)
                    ->groupBy('sub_option')
                    ->get() as $row)
                        <div class="row mt-4">
                            <div class="col-lg-12 text-center">
                                <h3 class="productDetails__keyFeatures--title mb-3">{{ $row->sub_option }}</h3>
                            </div>
                            <div class="col-lg-12 media-carousel mt-4" data-carousel-id="application_image_carousel_{{$row->id}}">
                                <div class="owl-carousel owl-theme application_image_carousel" id="application_image_carousel_{{$row->id}}">
                                    @foreach (\App\Models\PartAttribute::where('type', 'custom value')
                                    ->where('custom_field_id', $field->id)
                                    ->where('part_id', $part->id)
                                    ->where('ancestor_id', $row->id)
                                    ->whereNotNull('image')
                                    ->get() as $app_image)
                                        <div class="item">
                                            @if (in_array(strtolower(pathinfo(asset('uploads/product-custom-files/' . $app_image->image), PATHINFO_EXTENSION)), ['mp4', 'avi', 'mov']))
                                                <div class="text-center">
                                                    <video poster="" height="600px" width="" class="controls" controls>
                                                        <source src="{{ asset('uploads/product-custom-files/' . $app_image->image) }}" type="video/mp4" >
                                                    </video>
                                                </div> 
                                                
                                            @else
                                                <img src="{{ asset('uploads/product-custom-files/' . $app_image->image) }}" class="w-80 h-auto m-auto" alt="">   
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                @elseif ($field->field_name == 'Downloads')
                    <div class="row mt-4">
                        <div class="col-lg-12 text-center">
                            <h3 class="productDetails__keyFeatures--title mb-3">{{ $field->field_name }}</h3>
                        </div>
                        <div class="col-llg-12 downloads__data-list">
                            @foreach(\App\Models\PartAttribute::select('sub_option', DB::raw('MAX(id) as id'))
                            ->where('type', 'custom value')
                            ->where('custom_field_id', $field->id)
                            ->where('part_id', $part->id)
                            ->where('ancestor_id', null)
                            ->groupBy('sub_option')
                            ->get() as $row)
                                @foreach (\App\Models\PartAttribute::where('type', 'custom value')
                                ->where('custom_field_id', $field->id)
                                ->where('part_id', $part->id)
                                ->where('ancestor_id', $row->id)
                                ->whereNotNull('image')
                                ->get() as $files)
                                    <div class="data__item">
                                        <div class="col-1">
                                            <strong>{{ $row->sub_option }}</strong>                 
                                        </div>
                                        <div class="col-2">
                                            <a class="link link--download" target="_blank" href="{{ asset('uploads/product-custom-files/' . $files->image) }}">
                                                Download <i class="fa-solid fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.carousel-1').owlCarousel({
            loop: false,
            margin: 10,
            nav: false,
            responsive:{
                0:{
                    items:2
                },
                600:{
                    items:4
                },
                1000:{
                    items:6
                }
            }
        });
		
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

        $('.prev1').click(function() {
            $('.carousel-1').trigger('prev.owl.carousel');
        });

        $('.next1').click(function() {
            $('.carousel-1').trigger('next.owl.carousel');
        });

    });


    $(document).ready(function(){
        $('.item').click(function(){
            var imgSrc = $(this).find('img').attr('src');
            $('.enlarged_image').attr('src', imgSrc);
        });
		
		$('.enlarged_image').click(function() {
			$('#image_zoom_modal').modal('show');
		});
    });
</script>


@endsection

@extends('frontend.layout.app')
@section('content')
<div id="serviceDetails">
    <div class="breadcrumb__nk">
        <div class="container"><a href="{{ route('home') }}" class="text-light">{{ trans('language.home') }} </a> / <a href="{{ route('services')}}" class="text-light">{{ trans('language.service') }}</a>  / {{ $service->getTranslation(Session::get('language') ?? 'en', 'title') ?? $service->title }}</div>
    </div>
    
    <div class="serviceDetails__container container gap-5 mt-2">
		<div class="d-block d-lg-none">
            <h3 class="serviceDetails__title">{{ $service->getTranslation(Session::get('language') ?? 'en', 'title') ?? $service->title }}</h3>
            <p class="serviceDetails__code">{{ trans('language.service_code') }}: {{ $service->code }}</p>
            <p class="serviceDetails__info">{{ $service->getTranslation(Session::get('language') ?? 'en', 'short_description') ?? $service->short_description }}</p>
            <div class="serviceDetails__orderContainer">
                <a href="{{ route('add.to.service', $service->id) }}" class="btn">{{ trans('language.btn_order_now') }}</a>
            </div>
        </div>
		<!--
        <div class="serviceDetails_img_wrapper" style="overflow: hidden;">
            <div class="enlarged_image_container w-100 p-5">
                <img class="enlarged_image mx-auto" width="100%" src="{{ asset('uploads/service-images/'.$service->media) }}" alt="">
            </div>
        </div>-->
		<div class="serviceDetails_img_wrapper" style="overflow: hidden;">
			<div class="enlarged_image_container w-100 p-5 text-center">
				<img class="enlarged_image mx-auto" src="{{ asset('uploads/service-images/'.$service->media) }}"
					alt="">
			</div>
			@php 
				$aditional_descriptions = json_decode($service->additional_details);
			@endphp

			<div class="slider productDetails_slider-1">
				<button class="prev1"><i class="fa fa-angle-left"></i></button>
				<div class="carousel-1 owl-carousel owl-theme">
					<div class="item"><img src="{{ asset('uploads/service-images/' . $service->media) }}"
							alt=""></div>
					@if($aditional_descriptions)
						@foreach($aditional_descriptions as $row)
							<div class="item"><img src="{{ asset('uploads/service-images/' . $row->image) }}" alt="">
							</div>
						@endforeach
					@endif
				</div>
				<button class="next1"><i class="fa fa-angle-right"></i></button>
			</div>
		</div>
        <div class="pt-5 d-none d-lg-block">
            <h3 class="serviceDetails__title">{{ $service->getTranslation(Session::get('language') ?? 'en', 'title') ?? $service->title }}</h3>
            <p class="serviceDetails__code">{{ trans('language.service_code') }}: {{ $service->code }}</p>
            <p class="serviceDetails__info">{{ $service->getTranslation(Session::get('language') ?? 'en', 'short_description') ?? $service->short_description }}</p>
            <div class="serviceDetails__orderContainer">
                <a href="{{ route('add.to.service', $service->id) }}" class="btn">{{ trans('language.btn_order_now') }}</a>
            </div>
        </div>
    </div>
    <div class="row m-0">
        <div class="col-lg-12 p-0">
            <ul class="product__details__nav nav nav-pills justify-content-center" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active " id="pills-service-tab" data-bs-toggle="pill" data-bs-target="#pills-service-details" type="button" role="tab" aria-controls="pills-service-details" aria-selected="true">Service Informations</button>
                </li>
            </ul>
        </div>
    </div>
    <div class="product__details-content pt-4 pb-5">
        <div class="tab-content container" id="pills-tabContent">
            <div class="tab-pane fade show active " id="pills-service-details" role="tabpanel" aria-labelledby="pills-service-tab">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="productDetails__keyFeatures--title">{{ trans('language.description') }}</h3>
                        <p class="serviceDetails__description">
                            {!! $service->getTranslation(Session::get('language') ?? 'en', 'description') ?? $service->description !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>          
</div>
@push('footer')
    <script>
        $(document).ready(function() {
            $('.carousel-1').owlCarousel({
                loop: false,
                margin: 10,
                nav: false,
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

            $('.prev1').click(function() {
                $('.carousel-1').trigger('prev.owl.carousel');
            });

            $('.next1').click(function() {
                $('.carousel-1').trigger('next.owl.carousel');
            });

            
            $('.prev2').click(function() {
                $('.carousel-2').trigger('prev.owl.carousel');
            });

            $('.next2').click(function() {
                $('.carousel-2').trigger('next.owl.carousel');
            });

            $( ".media-carousel").each(function( index ){
                let id = $(this).attr('data-carousel-id');
                $('#'+id).owlCarousel({
                    loop:true,
                    nav:true,
                    margin:10,
                    autoplay:false,
                    autoplayTimeout:4000,
                    autoplayHoverPause:true,
                    items:1,
                    responsive:{
                        0:{
                            items:1
                        },
                        600:{
                            items:1
                        },
                        1000:{
                            items:1
                        }
                    }
                })
            });
            
        });


        $(document).ready(function() {
            $('.item').click(function() {
                var imgSrc = $(this).find('img').attr('src');
                $('.enlarged_image').attr('src', imgSrc);
            });
        });

        
    </script>
@endpush
@endsection

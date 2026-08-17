@extends('frontend.layout.app')
@section('content')
	<div class="breadcrumb__nk">
		<div class="container"><a href="{{ route('home') }}" class="text-light">{{ trans('language.home') }} </a> / {{ trans('language.about_us') }} </div>
	</div>
    <div id="about" class="p-5">
        <h1 class="about__title">{{ trans('language.about') }}</h1>
        <div class="about__content-wrapper">
            <div class="">
                {!! Helper::getSettings('about_us') !!}
            </div>
            <div class="d-flex justify-content-center position-relative">
                <div class="w-100 h-100">
                    <div class="position-relative">
                        <div id="mid-aboute-image">
                            <img class="w-100" src="{{ Helper::getSettings('about_image_1') ? asset('uploads/settings/'.Helper::getSettings('about_image_1')) : asset('assets/img/no-img.jpg')}}" alt="">
                        </div>
                        <img class="left-about-image" src="{{ Helper::getSettings('about_image_2') ? asset('uploads/settings/'.Helper::getSettings('about_image_2')) : asset('assets/img/no-img.jpg')}}" alt="">
                        <img class="right-about-image" src="{{ Helper::getSettings('about_image_3') ? asset('uploads/settings/'.Helper::getSettings('about_image_3')) : asset('assets/img/no-img.jpg')}}" alt="">
                    </div>
                    <div id="left-height"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var sourceDiv = document.getElementById("mid-aboute-image");
        var targetDiv = document.getElementById("left-height");
        var resizeObserver = new ResizeObserver(function(entries) {
            var sourceHeight = sourceDiv.offsetHeight;
            targetDiv.style.minHeight = sourceHeight + "px";
        });
        resizeObserver.observe(sourceDiv);
    </script>
@endsection

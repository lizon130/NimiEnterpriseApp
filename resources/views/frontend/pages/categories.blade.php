@extends('frontend.layout.app')
@section('content')
<div class="breadcrumb__nk">
    <div class="container"><a href="{{ route('home') }}" class="text-light">{{ trans('language.home') }}</a> / {{ trans('language.categories') }}</div>
</div>
<div id="product-category-page" class="container">
    <div class="container pb-5">
        <h1 class="page_title">{{ trans('language.categories') }}</h1>
        <div class="product-category__card-container">
            @foreach ($categories as $category)
                <div class="card">
                    <a href="{{ url('category/'.$category->slug) }}">
                        <img src="{{ asset('uploads/category-images/'.$category->image) }}" alt="">
                        <p class="text-uppercase">{{ $category->getTranslation(Session::get('language') ?? 'en', 'title') ?? $category->title }}</p>
                    </a>
                </div>

            @endforeach
        </div>
    </div>
</div>
@endsection

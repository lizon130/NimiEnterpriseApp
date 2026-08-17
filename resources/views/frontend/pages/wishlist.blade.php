@extends('frontend.layout.app')
@section('content')
<div id="wishlist">
    <div class="breadcrumb__nk">
        <div class="container"><a href="{{ route('home') }}" class="text-light">{{ trans('language.home') }} </a> / {{ trans('language.wishlist') }}</div>
    </div>
    <div class="container go_back_container">
        <a href="{{ route('products') }}"><i class="fa-solid fa-angle-left"></i> {{ trans('language.go_back') }}</a>
    </div>
    <div class="container wishlist__container">
        <h1 class="page_title">{{ trans('language.wishlist') }}</h1>
        @if(session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session()->get('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @forelse ($wishlistItems as $item)
            <div class="wishlist__card">
                <div class="wishlist__card--img">
                    @if($item['type'] == 'product')
                        <img src="{{ asset('uploads/product-images/'.$item['product']['thumbnail']) }}"  alt="" class="img-fluid">
                    @else
                        <img src="{{ asset('uploads/part-images/'.$item['product']['thumbnail']) }}"  alt="" class="img-fluid">
                    @endif
                </div>
                <div class="d-flex align-items-center">
                    <div class="">
                        <h6>{{ $item['product']['name']}}</h6>
                        <p class="mb-1">{{ trans('language.code') }} - {{ $item['product']['code']}}</p>
                        @if ($item['product']['price'])
                            @if($item['type'] == 'product')
                                @if (auth()->check() && $item['product']['price'] > 0)
                                    <p class="mb-0">{{ trans('language.price') }}: ${{Helper::priceAfterOffer($item['product']['id'])}}</p>
    									@if(Helper::priceAfterOffer($item['product']['id']) < $item['product']['price'] )
    										<p class="mb-0 price__discount"> {{ trans('language.old_price') }}:
    										<del>${{ $item['product']['price'] }}</del>
    									@endif
                                    </p>
                                @endif
                                <p class="mb-0">{{ trans('language.type') }}: {{ trans('language.product') }}</p>
                            @else
                                @if (auth()->check())
                                    <p class="mb-0">Price: ${{Helper::partPriceFaterOffer($item['product']['id'])}}</p>
                                @endif
                                <p class="mb-0">{{ trans('language.type') }}: {{ trans('language.product_part') }}</p>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="wishlist__card--request">
                    <div class="wishlist__card__request--count">
                        <span><a href="{{ route('decrement.from.wishlist', $item['product']['id'])}}" class="wishlist__card--sign"><i class="fa-solid fa-minus"></i></a></span>
                        <span class="wishlist__card--number">{{ $item['quantity'] }}</span>
                        <span><a href="{{ route('increment.from.wishlist', $item['product']['id'])}}" class="wishlist__card--sign"><i class="fa-solid fa-plus"></i></a></span>
                    </div>
                    <div>
                        @if (auth()->check() && $item['product']['price'] > 0)
                            @if($item['type'] == 'product')
                                @if(Helper::alreadyInCart($item['product']['id']) == true)
                                    <button class="btn" disabled>{{ trans('language.added_to_cart') }}</button>
                                @else
                                    <a href="{{ route('add.to.cart', ['type' => 'product', 'id' => $item['product']['id']] ) }}" class="btn add-to-cart">{{ trans('language.btn_add_to_cart') }}</a>
                                @endif
                            @else
                                @if(Helper::alreadyInCart($item['product']['id']) == true)
                                    <button class="btn" disabled>{{ trans('language.added_to_cart') }}</button>
                                @else
                                    <a href="{{ route('add.to.cart', ['type' => 'part', 'id' => $item['product']['id']] ) }}" class="btn add-to-cart">{{ trans('language.btn_add_to_cart') }}</a>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('add.to.inquiry', $item['product']['id']) }}" class="btn ">{{ trans('language.btn_request_inquiry') }}</a>
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('remove.from.wishlist', $item['product']['id'])}}" ><i class="fa-solid fa-trash-can"></i></a>
                    </div>
                </div>
            </div>
        @empty
            <p class="wish-alert">{{ trans('language.no_product_on_wishlist') }}!</p>
           <div class="col-lg-12 text-center wish-margin">
            <a class="btn__secondary" href="{{route('products')}}">Choose Your Product</a>
           </div>
        @endforelse
    </div>
</div>
@endsection

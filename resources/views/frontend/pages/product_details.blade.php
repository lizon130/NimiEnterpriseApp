@extends('frontend.layout.app')
@section('content')
    <div id="productDetails">
        <div class="breadcrumb__nk">
            <div class="container">{{ trans('language.home') }} / {{ trans('language.products') }} /
                {{ $product->getTranslation(Session::get('language') ?? 'en', 'name') ?? $product->name }}</div>
        </div>
        <div class="container go_back_container">
            <a href="{{ route('home') }}"><i class="fa-solid fa-angle-left"></i> {{ trans('language.go_back') }}</a>
        </div>
        <div class="productDetails__container container">
            <div class="productDetails_img_wrapper" style="overflow: hidden;">
                <div class="enlarged_image_container">
                    <img class="enlarged_image mx-auto" src="{{ asset('uploads/product-images/' . $product->thumbnail) }}"
                        alt="">
                </div>
                <div class="slider productDetails_slider-1">
                    <button class="prev1"><i class="fa fa-angle-left"></i></button>
                    <div class="carousel-1 owl-carousel owl-theme">
                        @if (count($product->images) > 0)
                            @foreach ($product->images as $image)
                                <div class="item"><img src="{{ asset('uploads/product-images/' . $image) }}" alt="">
                                </div>
                            @endforeach
                        @endif
                        <div class="item"><img src="{{ asset('uploads/product-images/' . $product->thumbnail) }}"
                                alt=""></div>
                    </div>
                    <button class="next1"><i class="fa fa-angle-right"></i></button>
                </div>
            </div>
            <div>
                <h3 class="productDetails_title">
                    {{ $product->getTranslation(Session::get('language') ?? 'en', 'name') ?? $product->name }}</h3>
                <p class="productDetails_code">{{ trans('language.product_code') }} - {{ $product->code }}</p>
                <div class="productDetails__specification">
                    @foreach ($product->attributes as $attribute)
                        @if ($attribute->attribute_name != null)
                            <p>{{ $attribute->attribute_name }}: {{ $attribute->value }}</p>
                        @endif
                    @endforeach

                </div>
                <hr>
                <div class="productDetails__price__order">
                    <div class="price__section">
                        @if ($product->price > 0)
                            <h5 class="price">Price: ${{ Helper::priceAfterOffer($product->id) }}</h5>
                            <p class="price__discount"> {{ trans('language.old_price') }}:
                                @if ($product->discount > 0 && $product->discount_type == 'percent')
                                    <del>${{ $product->price }}</del> <ins> -{{ $product->discount }}%</ins>
                                @elseif($product->discount > 0 && $product->discount_type == 'amount')
                                    <del>${{ $product->price }}</del> <ins> -${{ $product->discount }}</ins>
                                @endif
                            </p>
                        @endif
                    </div>

                    <div class="order__btn__area">

                        {{-- @if (auth()->check() && auth()->user()->role == 1)

                    @else --}}

                        @if ($product->price)
                            <a href="{{ route('add.to.cart', ['type' => 'product', 'id' => $product->id]) }}" class="btn order__btn add-to-cart">{{ trans('language.btn_order') }}</a>
                        @else
                            <a href="{{ route('add.to.inquiry', $product->id) }}" class="btn inquiry__btn">{{ trans('language.btn_add_to_inquiry_list') }}</a>
                        @endif
                        <a href="{{ route('add.to.wishlist', ['type' => 'product', 'id' => $product->id]) }}" class="btn wishlist__btn add-to-wishlist"><i class="fa-solid fa-heart"></i></a>
                    {{-- @endif --}}

                    </div>
                </div>
                <hr>
                <div class="productDetails__download">
                    <h5 class="productDetails__download--title">{{ trans('language.downloads') }}</h5>
                    @if ($catalogue)
                        <a href="{{ route('view.catalogue', $catalogue->id) }}"
                            class="btn mb-2">{{ trans('language.catalogues_page') }}</a> <br>
                    @endif
                    <a href="" class="btn">{{ trans('language.specification_sheet') }}</a>
                </div>
                <hr>
                <div class="productDetails__keyFeatures">
                    <h5 class="productDetails__keyFeatures--title">{{ trans('language.key_features') }}</h5>
                    {!! $product->getTranslation(Session::get('language') ?? 'en', 'key_features') ?? $product->key_features !!}
                </div>
                <hr>
                <div class="productDetails__furtherInformation">
                    <h5 class="productDetails__furtherInformation--title">{{ trans('language.further_information') }}</h5>
                    {!! $product->getTranslation(Session::get('language') ?? 'en', 'further_information') ??
                        $product->further_information !!}
                </div>
            </div>
        </div>
        @if (count($releted_parts) > 0)
            <div>
                <h1 class="page_title gap">{{ trans('language.related_product_parts') }}</h1>
                <div class="slider productDetails_slider-2 ">
                    <button class="prev2"><i class="fa fa-angle-left"></i></button>
                    <div class="carousel-2 owl-carousel owl-theme">
                        @foreach ($releted_parts as $part)
                            <div class="item">
                                <div class="products__card-container box">

                                    <a href="{{ route('parts.details', $part->id) }}">
                                        <img class="nothing" src="{{ asset('uploads/part-images/' . $part->thumbnail) }}"
                                            alt="">
                                        <p title="{{ $part->name }}" class="text-uppercase">
                                            {{ $part->getTranslation(Session::get('language') ?? 'en', 'name') ?? $part->name }}
                                        </p>
                                    </a>

                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button class="next2"><i class="fa fa-angle-right"></i></button>
                </div>
            </div>
        @endif
    </div>




    <script>
        $(document).ready(function() {
            $('.carousel-1').owlCarousel({
                loop: true,
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

            $('.carousel-2').owlCarousel({
                loop: true,
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

            $('.prev2').click(function() {
                $('.carousel-2').trigger('prev.owl.carousel');
            });

            $('.next2').click(function() {
                $('.carousel-2').trigger('next.owl.carousel');
            });
        });


        $(document).ready(function() {
            $('.item').click(function() {
                var imgSrc = $(this).find('img').attr('src');
                $('.enlarged_image').attr('src', imgSrc);
            });
        });
    </script>

@endsection

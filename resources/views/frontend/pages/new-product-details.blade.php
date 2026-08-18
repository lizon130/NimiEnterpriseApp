@extends('frontend.layout.app')
<meta property="og:url" content="{{ url('product/' . $product->id) }}">
<meta property="og:type" content="Website" />
<meta property="og:title" content="{{ $product->name ?? '' }}">
<meta property="og:image" content="{{ asset('uploads/product-images/' . $product->thumbnail) }}">
<meta property="og:image:width" content="600" />
<meta property="og:image:height" content="600" />
<meta property="product:brand" content="{{ $product->brand->title ?? '' }}">
<meta property="product:condition" content="new">
<meta property="product:price:currency" content="USD">

@push('header')
    <style>
        /* Modern Product Details Styles */
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --dark-color: #1f2937;
            --light-color: #f3f4f6;
            --border-color: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
        }

        /* Breadcrumb Styles */
        .breadcrumb__nk {
            background: linear-gradient(135deg, var(--dark-color) 0%, #374151 100%);
            padding: 1rem 0;
            margin-bottom: 2rem;
        }

        /* Product Title */
        .productDetails_title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            line-height: 1.2;
        }

        .productDetails_code {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

        /* Image Gallery */
        .enlarged_image_container {
            background: var(--light-color);
            border-radius: 1rem;
            padding: 1rem;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .enlarged_image_container:hover {
            transform: scale(1.02);
            box-shadow: var(--shadow-lg);
        }

        .enlarged_image {
            width: 100%;
            height: auto;
            object-fit: contain;
            border-radius: 0.5rem;
        }

        #product_image_slider .item {
            cursor: pointer;
            border-radius: 0.5rem;
            overflow: hidden;
            transition: all 0.3s ease;
            opacity: 0.7;
        }

        #product_image_slider .item:hover {
            opacity: 1;
            transform: translateY(-2px);
        }

        #product_image_slider .item img {
            width: 100%;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        #product_image_slider .item.active img {
            border-color: var(--primary-color);
            box-shadow: var(--shadow-md);
        }

        /* Price Section */
        .price__section {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            padding: 1.25rem;
            border-radius: 1rem;
            margin-bottom: 1.5rem;
        }

        .price {
            font-size: 2rem;
            font-weight: 700;
            color: var(--success-color);
            margin-bottom: 0.5rem;
        }

        .price__discount {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 0;
        }

        .price__discount del {
            color: var(--danger-color);
            font-weight: 500;
        }

        .discount-badge {
            display: inline-block;
            background: var(--danger-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.75rem;
            vertical-align: middle;
        }

        /* Buttons */
        .order__btn__area {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .order__btn {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            flex: 1;
            text-align: center;
        }

        .order__btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            color: white;
        }

        .inquiry__btn {
            background: var(--warning-color);
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            flex: 1;
            text-align: center;
        }

        .inquiry__btn:hover {
            background: #d97706;
            transform: translateY(-2px);
            color: white;
        }

        .wishlist__btn {
            background: white;
            color: var(--danger-color);
            padding: 0.875rem;
            border-radius: 0.75rem;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
            width: 48px;
        }

        .wishlist__btn:hover {
            background: var(--danger-color);
            color: white;
            border-color: var(--danger-color);
            transform: scale(1.05);
        }

        /* Tabs Navigation */
        .product__details__nav {
            background: white;
            border-bottom: 2px solid var(--border-color);
            padding: 0;
            margin-top: 3rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .product__details__nav .nav-item {
            margin-bottom: -2px;
        }

        .product__details__nav .nav-link {
            color: var(--dark-color);
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 0;
            background: transparent;
            transition: all 0.3s ease;
            position: relative;
        }

        .product__details__nav .nav-link:hover {
            color: var(--primary-color);
            background: transparent;
        }

        .product__details__nav .nav-link.active {
            color: var(--primary-color);
            background: transparent;
            border-bottom: 3px solid var(--primary-color);
        }

        /* Content Sections */
        .product__details-content {
            background: var(--light-color);
            min-height: 400px;
        }

        .productDetails__keyFeatures {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: var(--shadow-sm);
        }

        .productDetails__keyFeatures--title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 3px solid var(--primary-color);
            display: inline-block;
        }

        /* Attributes Table */
        .product-attributes__data-list {
            margin-top: 1.5rem;
        }

        .data__item {
            display: flex;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .data__item .col-1 {
            flex: 0 0 200px;
            font-weight: 600;
            color: var(--dark-color);
        }

        .data__item .col-2 {
            flex: 1;
            color: #6b7280;
        }

        /* Features Grid */
        .list--product-features {
            margin-top: 1.5rem;
        }

        .list__item {
            background: var(--light-color);
            padding: 1.5rem;
            border-radius: 0.75rem;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
        }

        .list__item:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        .list__item .icon {
            width: 60px;
            height: 60px;
            margin-bottom: 1rem;
        }

        .list__item .heading {
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--dark-color);
        }

        .list__item .text {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        /* Benefits List */
        .benifit-list-item {
            background: var(--light-color);
            padding: 1rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            height: 100%;
        }

        .benifit-list-item:hover {
            transform: translateX(4px);
            background: white;
            box-shadow: var(--shadow-sm);
        }

        .benifit-list-item .icon {
            margin-right: 1rem;
            color: var(--primary-color);
            font-size: 1.25rem;
        }

        .benifit-list-item .title {
            display: block;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.25rem;
        }

        .benifit-list-item .details {
            color: #6b7280;
            font-size: 0.875rem;
        }

        /* Accordion Styles */
        .product_detail_accordion .accordion-item {
            border: none;
            margin-bottom: 1rem;
            background: transparent;
        }

        .product_detail_accordion .accordion-header {
            background: white;
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .product_detail_accordion .accordion-button {
            background: white;
            font-weight: 600;
            padding: 1rem 1.5rem;
            box-shadow: var(--shadow-sm);
        }

        .product_detail_accordion .accordion-button:not(.collapsed) {
            background: var(--primary-color);
            color: white;
        }

        .product_detail_accordion .accordion-body {
            background: white;
            border-radius: 0 0 0.75rem 0.75rem;
            padding: 1.5rem;
            margin-top: 0.5rem;
            box-shadow: var(--shadow-sm);
        }

        /* Downloads Section */
        .downloads__data-list .data__item {
            justify-content: space-between;
            align-items: center;
        }

        .link--download {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--primary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .link--download:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            color: white;
        }

        /* Wishlist Card */
        .wishlist__card {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 1.5rem;
            background: white;
            border-radius: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }

        .wishlist__card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .wishlist__card--img {
            width: 100px;
            height: 100px;
            flex-shrink: 0;
        }

        .wishlist__card--img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0.5rem;
        }

        .wishlist__card--request {
            margin-left: auto;
        }

        /* Related Products */
        .page_title {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            margin: 3rem 0;
            position: relative;
        }

        .page_title:after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background: var(--primary-color);
            margin: 1rem auto 0;
            border-radius: 2px;
        }

        .product-cart {
            border: none;
            border-radius: 1rem;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }

        .product-cart:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .product-cart img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .product-title {
            padding: 1rem;
            margin: 0;
            font-weight: 600;
            color: var(--dark-color);
        }

        /* Download Section */
        .productDetails__download {
            background: var(--light-color);
            padding: 1rem;
            border-radius: 0.75rem;
            margin-top: 1rem;
        }

        /* Modal Styles */
        #image_zoom_modal .modal-content {
            background: rgba(0, 0, 0, 0.95);
        }

        #image_zoom_modal .btn-close {
            filter: invert(1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .productDetails_title {
                font-size: 1.5rem;
            }

            .price {
                font-size: 1.5rem;
            }

            .order__btn__area {
                flex-wrap: wrap;
            }

            .wishlist__card {
                flex-wrap: wrap;
            }

            .wishlist__card--request {
                margin-left: 0;
                width: 100%;
            }

            .data__item {
                flex-direction: column;
            }

            .data__item .col-1 {
                margin-bottom: 0.25rem;
            }
        }

        /* Loading Animation */
        .add-to-cart.loading,
        .add-to-wishlist.loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }

        .add-to-cart.loading:after,
        .add-to-wishlist.loading:after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid white;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* .d-none.d-lg-block {
            display: none !important;
        } */

        @media (min-width: 992px) {
            .d-none.d-lg-block {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }

            .price__section,
            .order__btn__area,
            .price,
            .price__discount,
            .order__btn,
            .inquiry__btn,
            .wishlist__btn {
                display: flex !important;
                visibility: visible !important;
                opacity: 1 !important;
            }

            .price__section {
                display: block !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="modal fade" id="image_zoom_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="staticBackdropLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="owl-carousel owl-theme" id="image_zoom_modal_slider">
                        <div class="item d-flex justify-content-center">
                            <img class="zoom_image" src="{{ asset('uploads/product-images/' . $product->thumbnail) }}"
                                style="max-height: 90vh; width: auto;" alt="">
                        </div>
                        @if ($product->images != null && count($product->images) > 0)
                            @foreach ($product->images as $image)
                                <div class="item d-flex justify-content-center">
                                    <img class="zoom_image" src="{{ asset('uploads/product-images/' . $image) }}"
                                        style="max-height: 90vh; width: auto;" alt="">
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="productDetails">
        <!-- Breadcrumb -->
        <div class="breadcrumb__nk">
            <div class="container">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('home') }}" class="text-light text-decoration-none">
                        <i class="fa-solid fa-home"></i> {{ trans('language.home') }}
                    </a>
                    <i class="fa-solid fa-chevron-right text-light" style="font-size: 12px;"></i>
                    <a href="{{ route('products') }}" class="text-light text-decoration-none">
                        {{ trans('language.products') }}
                    </a>
                    <i class="fa-solid fa-chevron-right text-light" style="font-size: 12px;"></i>
                    <span class="text-light-50">
                        {{ $product->getTranslation(Session::get('language') ?? 'en', 'name') ?? $product->name }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Go Back Button -->
        <div class="container mb-3">
            <a href="{{ URL::previous() }}" class="text-decoration-none text-muted">
                <i class="fa-solid fa-arrow-left me-2"></i> {{ trans('language.go_back') }}
            </a>
        </div>

        <!-- Main Product Section -->
        <div class="container">
            <div class="row g-4">
                <!-- Left Column - Images -->
                <div class="col-lg-7">
                    <!-- Mobile Title -->
                    <div class="d-block d-lg-none mb-3">
                        <h1 class="productDetails_title">
                            {{ $product->getTranslation(Session::get('language') ?? 'en', 'name') ?? $product->name }}
                        </h1>
                        <p class="productDetails_code">
                            <i class="fa-solid fa-barcode me-2"></i>
                            {{ trans('language.product_code') }}: {{ $product->code }}
                        </p>
                    </div>

                    <!-- Main Image -->
                    <div class="enlarged_image_container mb-3">
                        <img class="enlarged_image mx-auto d-block" id="main_product_image"
                            src="{{ asset('uploads/product-images/' . $product->thumbnail) }}" alt="{{ $product->name }}">
                    </div>

                    <!-- Thumbnail Slider -->
                    <div class="mt-3">
                        <div class="owl-carousel owl-theme" id="product_image_slider">
                            <div class="item" data-image="{{ asset('uploads/product-images/' . $product->thumbnail) }}">
                                <img src="{{ asset('uploads/product-images/' . $product->thumbnail) }}" height="80px"
                                    width="auto" alt="">
                            </div>
                            @if ($product->images != null && count($product->images) > 0)
                                @foreach ($product->images as $image)
                                    <div class="item" data-image="{{ asset('uploads/product-images/' . $image) }}">
                                        <img src="{{ asset('uploads/product-images/' . $image) }}" height="80px"
                                            width="auto" alt="">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Mobile Price & Order -->
                    {{-- Mobile Price & Order --}}
<div class="d-block d-lg-none mt-4">
    @php
        $originalPrice = $product->price;
        $discount = $product->discount ?? 0;
        $discountType = $product->discount_type ?? 'percent';
        $discountedPrice = Helper::priceAfterOffer($product->id);
    @endphp
    <div class="price__section">
        @if ($originalPrice > 0)
            <h3 class="price mb-0">
                ৳{{ number_format($discountedPrice, 2) }}
            </h3>

            @if ($discount > 0)
                <p class="price__discount mt-2 mb-0">
                    {{ trans('language.old_price') }}:
                    <del>৳{{ number_format($originalPrice, 2) }}</del>

                    @if ($discountType == 'percent')
                        <span class="discount-badge">{{ $discount }}% OFF</span>
                    @elseif ($discountType == 'amount')
                        <span class="discount-badge">৳{{ number_format($discount, 0) }} OFF</span>
                    @endif
                </p>
            @endif
        @endif
    </div>

    <div class="order__btn__area">
        @if ($originalPrice > 0)
            <a href="{{ route('add.to.cart', ['type' => 'product', 'id' => $product->id]) }}"
                class="btn order__btn add-to-cart">
                <i class="fa-solid fa-cart-shopping me-2"></i>
                {{ trans('language.btn_order') }}
            </a>
        @else
            <a href="{{ route('add.to.inquiry', $product->id) }}"
                class="btn inquiry__btn">
                <i class="fa-solid fa-envelope me-2"></i>
                {{ trans('language.btn_add_to_inquiry_list') }}
            </a>
        @endif

        <a href="{{ route('add.to.wishlist', ['type' => 'product', 'id' => $product->id]) }}"
            class="btn wishlist__btn add-to-wishlist">
            <i class="fa-solid fa-heart"></i>
        </a>
    </div>
</div>
                </div>

                <!-- Right Column - Details -->
                <div class="col-lg-5">
                    <!-- Desktop Title -->
                    <div class="d-none d-lg-block">
                        <h1 class="productDetails_title">
                            {{ $product->getTranslation(Session::get('language') ?? 'en', 'name') ?? $product->name }}
                        </h1>
                        <p class="productDetails_code">
                            <i class="fa-solid fa-barcode me-2"></i>
                            {{ trans('language.product_code') }}: {{ $product->code }}
                        </p>
                        <hr class="my-3">
                    </div>

                    <!-- Desktop Price & Order -->
                    <div class="d-none d-lg-block">
                        @php
                            $originalPrice = $product->price;
                            $discount = $product->discount ?? 0;
                            $discountType = $product->discount_type ?? 'percent';
                            $discountedPrice = Helper::priceAfterOffer($product->id);
                            $discountAmount = Helper::productDiscountAmount($product->id);

                            $showPriceForUser = false;
                            if (!auth()->check()) {
                                $showPriceForUser = ($originalPrice > 0);
                            } else {
                                if (auth()->user()->role == 2 && $product->show_price == 1) {
                                    $showPriceForUser = true;
                                } elseif (auth()->user()->role != 2 && $product->show_price_to_partner == 1) {
                                    $showPriceForUser = true;
                                }
                            }

                            $showOrderBtn = false;
                            if (!auth()->check()) {
                                $showOrderBtn = ($originalPrice > 0);
                            } else {
                                if (auth()->user()->role == 2 && $product->show_price == 1) {
                                    $showOrderBtn = true;
                                } elseif (auth()->user()->role != 2 && $product->show_price_to_partner == 1) {
                                    $showOrderBtn = true;
                                }
                            }
                        @endphp
                        <div class="price__section">
                            @if ($originalPrice > 0 && $showPriceForUser)
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h3 class="price mb-0">৳{{ number_format($discountedPrice, 2) }}</h3>
                                    @if ($discount > 0)
                                        @if ($discountType == 'percent')
                                            <span class="discount-badge">{{ $discount }}% OFF</span>
                                        @elseif ($discountType == 'amount')
                                            <span class="discount-badge">৳{{ number_format($discount, 0) }} OFF</span>
                                        @endif
                                    @endif
                                </div>
                                @if ($discount > 0)
                                    <p class="price__discount mb-0">
                                        {{ trans('language.old_price') }}:
                                        <del>৳{{ number_format($originalPrice, 2) }}</del>
                                        <span class="text-success ms-2">
                                            <i class="fa-solid fa-tag me-1"></i>Save
                                            ৳{{ number_format($discountAmount, 2) }}
                                        </span>
                                    </p>
                                @endif
                            @endif
                        </div>

                        <div class="order__btn__area">
                            @if ($showOrderBtn)
                                <a href="{{ route('add.to.cart', ['type' => 'product', 'id' => $product->id]) }}"
                                    class="btn order__btn add-to-cart">
                                    <i class="fa-solid fa-cart-shopping me-2"></i>{{ trans('language.btn_order') }}
                                </a>
                            @else
                                <a href="{{ route('add.to.inquiry', $product->id) }}" class="btn inquiry__btn">
                                    <i class="fa-solid fa-envelope me-2"></i>{{ trans('language.btn_add_to_inquiry_list') }}
                                </a>
                            @endif
                            <a href="{{ route('add.to.wishlist', ['type' => 'product', 'id' => $product->id]) }}"
                                class="btn wishlist__btn add-to-wishlist">
                                <i class="fa-solid fa-heart"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Downloads -->
                    @if ($catalogue)
                        <div class="productDetails__download mt-4">
                            <h5 class="productDetails__download--title mb-3">
                                <i class="fa-solid fa-download me-2"></i>{{ trans('language.downloads') }}
                            </h5>
                            <a href="{{ route('view.catalogue', $catalogue->id) }}"
                                class="btn btn-outline-primary w-100 mb-2">
                                <i class="fa-solid fa-file-pdf me-2"></i>{{ trans('language.catalogues_page') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="row m-0 mt-5">
            <div class="col-lg-12 p-0">
                <ul class="product__details__nav nav nav-pills justify-content-center" id="pills-tab" role="tablist">
                    @foreach ($custom_fields as $field)
                        @if ($field->field_name != 'Product Information')
                            @if ($product->custom_options && count($product->custom_options()->where('custom_field_id', $field->id)->get()) > 0)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link @if ($loop->iteration == 1) active @endif"
                                        id="pills-{{ $field->id }}-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-{{ $field->id }}" type="button" role="tab"
                                        aria-controls="pills-{{ $field->id }}"
                                        aria-selected="true">{{ $field->field_name }}</button>
                                </li>
                            @endif
                        @else
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if ($loop->iteration == 1) active @endif"
                                    id="pills-{{ $field->id }}-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-{{ $field->id }}" type="button" role="tab"
                                    aria-controls="pills-{{ $field->id }}"
                                    aria-selected="true">{{ $field->field_name }}</button>
                            </li>
                        @endif
                    @endforeach
                    @if ($product->servies && count($product->servies) > 0)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-service-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-service" type="button" role="tab"
                                aria-controls="pills-service" aria-selected="false">
                                <i class="fa-solid fa-headset me-2"></i>Services
                            </button>
                        </li>
                    @endif
                    @if ($product->parts && count($product->parts()->where('parts_type', 'parts')->get()) > 0)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-profile" type="button" role="tab"
                                aria-controls="pills-profile" aria-selected="false">
                                <i class="fa-solid fa-microchip me-2"></i>Spare parts
                            </button>
                        </li>
                    @endif
                    @if ($product->parts && count($product->parts()->where('parts_type', 'accessories')->get()) > 0)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-contact" type="button" role="tab"
                                aria-controls="pills-contact" aria-selected="false">
                                <i class="fa-solid fa-plug me-2"></i>Accessories
                            </button>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="product__details-content pt-4 pb-5">
            <div class="tab-content container" id="pills-tabContent">
                @foreach ($custom_fields as $field)
                    <div class="tab-pane fade @if ($loop->iteration == 1) show active @endif"
                        id="pills-{{ $field->id }}" role="tabpanel" aria-labelledby="pills-{{ $field->id }}-tab">

                        @if ($field->field_name == 'Product Information')
                            <div class="row">
                                <div class="col-lg-12 productDetails__keyFeatures">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <h3 class="productDetails__keyFeatures--title">
                                                <i class="fa-solid fa-chart-line me-2"></i>
                                                {{ trans('language.Key_data_at_a_glance') }}
                                            </h3>
                                            {!! $product->getTranslation(Session::get('language') ?? 'en', 'key_features') ?? $product->key_features !!}

                                            <h3 class="productDetails__keyFeatures--title mt-4">
                                                <i class="fa-solid fa-info-circle me-2"></i>
                                                {{ trans('Further Information') }}
                                            </h3>
                                            {!! $product->getTranslation(Session::get('language') ?? 'en', 'further_information') ??
                                                $product->further_information !!}

                                            <!-- Product Attributes -->
                                            @if (count($product->attributes) > 0)
                                                <h3 class="productDetails__keyFeatures--title mt-4">
                                                    <i class="fa-solid fa-list me-2"></i>
                                                    Technical Specifications
                                                </h3>
                                                <div class="product-attributes__data-list">
                                                    @foreach ($product->attributes as $attribute)
                                                        @if ($attribute->attribute_name != null)
                                                            <div class="data__item">
                                                                <div class="col-1">{{ $attribute->attribute_name }}</div>
                                                                <div class="col-2">{{ $attribute->value }}</div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif

                                            <!-- Custom Fields Content -->
                                            @foreach (\App\Models\ProductAttribute::select('sub_option', DB::raw('MAX(id) as id'))->where('type', 'custom value')->where('custom_field_id', $field->id)->where('product_id', $product->id)->where('ancestor_id', null)->groupBy('sub_option')->get() as $row)
                                                @if ($row->sub_option == 'More Options')
                                                    <h3 class="productDetails__keyFeatures--title mt-4">
                                                        <i class="fa-solid fa-cogs me-2"></i>
                                                        {{ $row->sub_option }}
                                                    </h3>
                                                    <div class="list--product-features row g-4 m-0 mt-3">
                                                        @foreach (\App\Models\ProductAttribute::where('product_id', $product->id)->where('type', 'custom value')->where('custom_field_id', $field->id)->where('language_code', Session::get('admin_language') ?? 'en')->where('ancestor_id', $row->id)->get() as $option)
                                                            <div class="col-lg-4 col-md-6">
                                                                <div class="list__item">
                                                                    @if ($option->image)
                                                                        <img src="{{ asset('uploads/product-custom-files/' . $option->image) }}"
                                                                            class="icon" alt="">
                                                                    @endif
                                                                    <h6 class="heading">{{ $option->title }}</h6>
                                                                    <p class="text">{{ $option->value }}</p>
                                                                    <p class="zusatztext text-muted small">
                                                                        {{ $option->details }}</p>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @elseif($row->sub_option == 'Models')
                                                    <h3 class="productDetails__keyFeatures--title mt-4">
                                                        <i class="fa-solid fa-cube me-2"></i>
                                                        {{ $row->sub_option }}
                                                    </h3>
                                                    <div class="product_detail_accordion accordion w-100 mt-3"
                                                        id="accordionExample">
                                                        @foreach (\App\Models\ProductAttribute::select('title')->where('type', 'custom value')->where('custom_field_id', $field->id)->where('product_id', $product->id)->where('language_code', Session::get('admin_language') ?? 'en')->where('ancestor_id', $row->id)->whereNull('value')->whereNull('details')->groupBy('title')->get() as $sfeatures)
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header"
                                                                    id="headingOne{{ $loop->iteration }}">
                                                                    <button class="accordion-button collapsed"
                                                                        type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#collapseOne{{ $loop->iteration }}"
                                                                        aria-expanded="false"
                                                                        aria-controls="collapseOne{{ $loop->iteration }}">
                                                                        {{ $sfeatures->title }}
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseOne{{ $loop->iteration }}"
                                                                    class="accordion-collapse collapse"
                                                                    aria-labelledby="headingOne{{ $loop->iteration }}"
                                                                    data-bs-parent="#accordionExample">
                                                                    <div class="accordion-body ps-0">
                                                                        <div class="product-attributes__data-list">
                                                                            @foreach (\App\Models\ProductAttribute::where('type', 'custom value')->where('custom_field_id', $field->id)->where('product_id', $product->id)->where('language_code', Session::get('admin_language') ?? 'en')->where('ancestor_id', $row->id)->where('title', $sfeatures->title)->get() as $sfeatureoption)
                                                                                @if ($sfeatureoption->title != null && $sfeatureoption->value != null)
                                                                                    <div class="data__item">
                                                                                        <div class="col-1">
                                                                                            {{ $sfeatureoption->value }}
                                                                                        </div>
                                                                                        <div class="col-2">
                                                                                            {{ $sfeatureoption->details }}
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @elseif($row->sub_option == 'Single components features')
                                                    <h3 class="productDetails__keyFeatures--title mt-4">
                                                        <i class="fa-solid fa-puzzle-piece me-2"></i>
                                                        {{ $row->sub_option }}
                                                    </h3>
                                                    <div class="product_detail_accordion accordion w-100 mt-3"
                                                        id="accordionExample2">
                                                        @foreach (\App\Models\ProductAttribute::select('title')->where('type', 'custom value')->where('custom_field_id', $field->id)->where('product_id', $product->id)->where('language_code', Session::get('admin_language') ?? 'en')->where('ancestor_id', $row->id)->whereNull('value')->whereNull('details')->groupBy('title')->get() as $sfeatures)
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header"
                                                                    id="headingTwo{{ $loop->iteration }}">
                                                                    <button class="accordion-button collapsed"
                                                                        type="button" data-bs-toggle="collapse"
                                                                        data-bs-target="#collapseTwo{{ $loop->iteration }}"
                                                                        aria-expanded="false"
                                                                        aria-controls="collapseTwo{{ $loop->iteration }}">
                                                                        {{ $sfeatures->title }}
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseTwo{{ $loop->iteration }}"
                                                                    class="accordion-collapse collapse"
                                                                    aria-labelledby="headingTwo{{ $loop->iteration }}"
                                                                    data-bs-parent="#accordionExample2">
                                                                    <div class="accordion-body ps-0">
                                                                        <div class="product-attributes__data-list">
                                                                            @foreach (\App\Models\ProductAttribute::where('type', 'custom value')->where('custom_field_id', $field->id)->where('product_id', $product->id)->where('language_code', Session::get('admin_language') ?? 'en')->where('ancestor_id', $row->id)->where('title', $sfeatures->title)->get() as $sfeatureoption)
                                                                                @if ($sfeatureoption->title != null && $sfeatureoption->value != null)
                                                                                    <div class="data__item">
                                                                                        <div class="col-1">
                                                                                            {{ $sfeatureoption->value }}
                                                                                        </div>
                                                                                        <div class="col-2">
                                                                                            {{ $sfeatureoption->details }}
                                                                                        </div>
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
                                                    <div class="alert alert-info mt-4">
                                                        <i class="fa-solid fa-bell me-2"></i>
                                                        <strong>{{ $row->sub_option }}:</strong>
                                                        <p class="mb-0 mt-2">
                                                            {{ \App\Models\ProductAttribute::where('product_id', $product->id)->where('type', 'custom value')->where('custom_field_id', $field->id)->where('language_code', Session::get('admin_language') ?? 'en')->where('ancestor_id', $row->id)->where('sub_option', 'Notice')->whereNull('title')->first()->details ?? '' }}
                                                        </p>
                                                    </div>
                                                @elseif($row->sub_option == 'Scope of delivery')
                                                    <div class="card mt-4">
                                                        <div class="card-body">
                                                            <h5 class="card-title">
                                                                <i class="fa-solid fa-box me-2"></i>
                                                                {{ $row->sub_option }}
                                                            </h5>
                                                            <ul class="mb-0 mt-3">
                                                                @if (isset(
                                                                        \App\Models\ProductAttribute::where('product_id', $product->id)->where('type', 'custom value')->where('custom_field_id', $field->id)->where('language_code', Session::get('admin_language') ?? 'en')->where('ancestor_id', $row->id)->where('sub_option', 'Scope of delivery')->whereNull('title')->first()->details) &&
                                                                        \App\Models\ProductAttribute::where('product_id', $product->id)->where('type', 'custom value')->where('custom_field_id', $field->id)->where('language_code', Session::get('admin_language') ?? 'en')->where('ancestor_id', $row->id)->where('sub_option', 'Scope of delivery')->whereNull('title')->first()->details != null)
                                                                    @foreach (explode(
            ',',
            \App\Models\ProductAttribute::where('product_id', $product->id)->where('type', 'custom value')->where('custom_field_id', $field->id)->where('language_code', Session::get('admin_language') ?? 'en')->where('ancestor_id', $row->id)->where('sub_option', 'Scope of delivery')->whereNull('title')->first()->details,
        ) as $key => $val)
                                                                        <li>{{ $val ?? '' }}</li>
                                                                    @endforeach
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="sticky-top" style="top: 20px;">
                                                <img class="img-fluid rounded shadow-sm"
                                                    src="{{ asset('uploads/product-images/' . $product->thumbnail) }}"
                                                    alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif ($field->field_name == 'Benefits')
                            @foreach (\App\Models\ProductAttribute::select('sub_option', DB::raw('MAX(id) as id'))->where('type', 'custom value')->where('custom_field_id', $field->id)->where('product_id', $product->id)->where('ancestor_id', null)->groupBy('sub_option')->get() as $row)
                                <div class="row mt-4">
                                    <div class="col-lg-12 text-center">
                                        <div class="icon-box mb-3">
                                            <i class="fa-solid fa-star text-primary" style="font-size: 3rem;"></i>
                                        </div>
                                        <h3 class="productDetails__keyFeatures--title mb-3">{{ $row->sub_option }}</h3>
                                        <p class="lead">
                                            {{ \App\Models\ProductAttribute::where('type', 'custom value')->where('custom_field_id', $field->id)->where('product_id', $product->id)->where('ancestor_id', $row->id)->whereNull('title')->first()->details ?? '' }}
                                        </p>
                                    </div>
                                    <div class="col-lg-12 p-0 mt-4">
                                        <div class="row g-4">
                                            @foreach (\App\Models\ProductAttribute::where('type', 'custom value')->where('custom_field_id', $field->id)->where('product_id', $product->id)->where('ancestor_id', $row->id)->whereNotNull('title')->get() as $benifit)
                                                <div class="col-lg-6">
                                                    <div class="benifit-list-item">
                                                        <div class="icon">
                                                            <i class="fa-solid fa-check-circle"></i>
                                                        </div>
                                                        <div>
                                                            <strong class="title">{{ $benifit->title }}</strong>
                                                            <span
                                                                class="details d-block mt-1">{{ $benifit->details }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @elseif ($field->field_name == 'Application')
                            @foreach (\App\Models\ProductAttribute::select('sub_option', DB::raw('MAX(id) as id'))->where('type', 'custom value')->where('custom_field_id', $field->id)->where('product_id', $product->id)->where('ancestor_id', null)->groupBy('sub_option')->get() as $row)
                                <div class="row mt-4">
                                    <div class="col-lg-12 text-center">
                                        <h3 class="productDetails__keyFeatures--title mb-3">{{ $row->sub_option }}</h3>
                                    </div>
                                    <div class="col-lg-12 media-carousel mt-4"
                                        data-carousel-id="application_image_carousel_{{ $row->id }}">
                                        <div class="owl-carousel owl-theme application_image_carousel"
                                            id="application_image_carousel_{{ $row->id }}">
                                            @foreach (\App\Models\ProductAttribute::where('type', 'custom value')->where('custom_field_id', $field->id)->where('product_id', $product->id)->where('ancestor_id', $row->id)->get() as $app_image)
                                                <div class="item">
                                                    @if (in_array(strtolower(pathinfo(asset('uploads/product-custom-files/' . $app_image->image), PATHINFO_EXTENSION)), [
                                                            'mp4',
                                                            'avi',
                                                            'mov',
                                                        ]))
                                                        <div class="text-center">
                                                            <video poster="" height="500px" class="w-100" controls>
                                                                <source
                                                                    src="{{ asset('uploads/product-custom-files/' . $app_image->image) }}"
                                                                    type="video/mp4">
                                                            </video>
                                                        </div>
                                                    @elseif(in_array(strtolower(pathinfo(asset('uploads/product-custom-files/' . $app_image->image), PATHINFO_EXTENSION)), [
                                                            'png',
                                                            'jpg',
                                                            'jpeg',
                                                            'webp',
                                                            'gif',
                                                        ]))
                                                        <img src="{{ asset('uploads/product-custom-files/' . $app_image->image) }}"
                                                            class="img-fluid rounded shadow-sm m-auto d-block"
                                                            alt="">
                                                    @else
                                                        <div class="text-center">
                                                            <iframe width="100%" height="500px"
                                                                src="{{ $app_image->details }}" frameborder="0"
                                                                allowfullscreen></iframe>
                                                        </div>
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
                                    <i class="fa-solid fa-download text-primary" style="font-size: 3rem;"></i>
                                    <h3 class="productDetails__keyFeatures--title mb-3 mt-3">{{ $field->field_name }}
                                    </h3>
                                </div>
                                <div class="product_detail_accordion accordion w-100 mt-4" id="accordionDownloads">
                                    @foreach (\App\Models\ProductAttribute::select(\DB::raw("SUBSTRING_INDEX(sub_option, ' - ', 1) AS modified_sub_option"), \DB::raw('MAX(id) as id'))->where('type', 'custom value')->where('custom_field_id', $field->id)->where('product_id', $product->id)->whereNull('ancestor_id')->groupBy('modified_sub_option')->get() as $row)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingDownload{{ $loop->iteration }}">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapseDownload{{ $loop->iteration }}"
                                                    aria-expanded="false"
                                                    aria-controls="collapseDownload{{ $loop->iteration }}">
                                                    <i class="fa-solid fa-folder-open me-2"></i>
                                                    {{ $row->modified_sub_option }}
                                                </button>
                                            </h2>
                                            <div id="collapseDownload{{ $loop->iteration }}"
                                                class="accordion-collapse collapse"
                                                aria-labelledby="headingDownload{{ $loop->iteration }}"
                                                data-bs-parent="#accordionDownloads">
                                                <div class="accordion-body">
                                                    <div class="downloads__data-list">
                                                        @foreach (\App\Models\ProductAttribute::where('type', 'custom value')->where('custom_field_id', $field->id)->where('product_id', $product->id)->whereNull('ancestor_id')->where(\DB::raw("SUBSTRING_INDEX(sub_option, ' - ', 1)"), '=', $row->modified_sub_option)->get() as $row2)
                                                            @foreach (\App\Models\ProductAttribute::where('type', 'custom value')->where('custom_field_id', $field->id)->where('product_id', $product->id)->where('ancestor_id', $row2->id)->whereNotNull('image')->get() as $files)
                                                                <div class="data__item">
                                                                    <div class="col-5">
                                                                        <strong>{{ $row2->sub_option }}</strong>
                                                                    </div>
                                                                    <div class="col-2">
                                                                        @php
                                                                            $fileExtension = pathinfo(
                                                                                $files->image,
                                                                                PATHINFO_EXTENSION,
                                                                            );
                                                                            $isPDF =
                                                                                strtolower($fileExtension) === 'pdf';
                                                                        @endphp
                                                                        @if ($isPDF)
                                                                            <a class="link link--download" target="_blank"
                                                                                href="{{ asset('uploads/product-custom-files/' . $files->image) }}">
                                                                                <i
                                                                                    class="fa-solid fa-file-pdf me-2"></i>Download
                                                                                <i class="fa-solid fa-download"></i>
                                                                            </a>
                                                                        @else
                                                                            <a class="link link--download"
                                                                                href="{{ route('download.file', ['fileName' => $files->image]) }}">
                                                                                <i
                                                                                    class="fa-solid fa-file me-2"></i>Download
                                                                                <i class="fa-solid fa-download"></i>
                                                                            </a>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach

                <!-- Services Tab -->
                @if ($product->servies && count($product->servies) > 0)
                    <div class="tab-pane fade" id="pills-service" role="tabpanel" aria-labelledby="pills-service-tab">
                        <div class="row mt-4">
                            <div class="col-lg-12 mb-5 text-center">
                                <i class="fa-solid fa-headset text-primary" style="font-size: 3rem;"></i>
                                <h3 class="productDetails__keyFeatures--title mb-3 mt-3">{{ trans('language.services') }}
                                </h3>
                                <p class="lead">We will be happy to advise you individually and adapt your products for
                                    the best possible application.</p>
                                <a href="{{ route('contact-us') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-envelope me-2"></i>{{ trans('language.contact') }}
                                </a>
                            </div>
                            @foreach ($product->servies as $service)
                                <div class="col-lg-12 row mb-5 align-items-center">
                                    <div class="col-lg-6">
                                        @if ($loop->iteration % 2 != 0)
                                            <div class="service-image">
                                                <img class="img-fluid rounded shadow-lg"
                                                    src="{{ asset('uploads/service-images/' . $service->media) }}"
                                                    alt="{{ $service->title }}">
                                            </div>
                                        @else
                                            <div class="service-content">
                                                <h2 class="mb-3">{{ $service->title }}</h2>
                                                <p class="text-muted">{{ $service->short_description }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-lg-6">
                                        @if ($loop->iteration % 2 != 0)
                                            <div class="service-content">
                                                <h2 class="mb-3">{{ $service->title }}</h2>
                                                <p class="text-muted">{{ $service->short_description }}</p>
                                            </div>
                                        @else
                                            <div class="service-image">
                                                <img class="img-fluid rounded shadow-lg"
                                                    src="{{ asset('uploads/service-images/' . $service->media) }}"
                                                    alt="{{ $service->title }}">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Spare Parts Tab -->
                @if ($product->parts && count($product->parts()->where('parts_type', 'parts')->get()) > 0)
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <div class="row mt-4">
                            <div class="col-lg-12 mb-5 text-center">
                                <i class="fa-solid fa-microchip text-primary" style="font-size: 3rem;"></i>
                                <h3 class="productDetails__keyFeatures--title mb-3 mt-3">
                                    {{ trans('Spare parts and individual components') }}
                                </h3>
                            </div>
                        </div>
                        <div class="row">
                            @foreach ($product->parts->where('parts_type', 'parts') as $item)
                                <div class="col-lg-12">
                                    <div class="wishlist__card">
                                        <div class="wishlist__card--img">
                                            <img src="{{ asset('uploads/part-images/' . $item->thumbnail) }}"
                                                alt="" class="img-fluid">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $item->name }}</h6>
                                            <p class="mb-1 text-muted small">Code: {{ $item->code }}</p>
                                            @if ($item->price)
                                                <p class="mb-0 text-success fw-bold">
                                                    ${{ Helper::partPriceFaterOffer($item->id) }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="wishlist__card--request">
                                            @if ($item->price)
                                                <a href="{{ route('add.to.cart', ['type' => 'part', 'id' => $item->id]) }}"
                                                    class="btn btn-primary add-to-cart">
                                                    <i
                                                        class="fa-solid fa-cart-plus me-2"></i>{{ trans('language.btn_add_to_cart') }}
                                                </a>
                                            @else
                                                <a href="{{ route('add.to.inquiry', $item->id) }}"
                                                    class="btn btn-warning">
                                                    <i
                                                        class="fa-solid fa-envelope me-2"></i>{{ trans('language.btn_request_inquiry') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Accessories Tab -->
                @if ($product->parts && count($product->parts()->where('parts_type', 'accessories')->get()) > 0)
                    <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                        <div class="row mt-4">
                            <div class="col-lg-12 mb-5 text-center">
                                <i class="fa-solid fa-plug text-primary" style="font-size: 3rem;"></i>
                                <h3 class="productDetails__keyFeatures--title mb-3 mt-3">{{ trans('Accessories') }}</h3>
                            </div>
                        </div>
                        <div class="row">
                            @foreach ($product->parts->where('parts_type', 'accessories') as $item)
                                <div class="col-lg-12">
                                    <div class="wishlist__card">
                                        <div class="wishlist__card--img">
                                            <img src="{{ asset('uploads/part-images/' . $item->thumbnail) }}"
                                                alt="" class="img-fluid">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $item->name }}</h6>
                                            <p class="mb-1 text-muted small">Code: {{ $item->code }}</p>
                                            @if ($item->price)
                                                <p class="mb-0 text-success fw-bold">
                                                    ${{ Helper::partPriceFaterOffer($item->id) }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="wishlist__card--request">
                                            @if ($item->price)
                                                <a href="{{ route('add.to.cart', ['type' => 'part', 'id' => $item->id]) }}"
                                                    class="btn btn-primary add-to-cart">
                                                    <i
                                                        class="fa-solid fa-cart-plus me-2"></i>{{ trans('language.btn_add_to_cart') }}
                                                </a>
                                            @else
                                                <a href="{{ route('add.to.inquiry', $item->id) }}"
                                                    class="btn btn-warning">
                                                    <i
                                                        class="fa-solid fa-envelope me-2"></i>{{ trans('language.btn_request_inquiry') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('footer')
        <script>
            $(document).ready(function() {
                // Initialize main image slider
                $('#product_image_slider').owlCarousel({
                    loop: false,
                    margin: 10,
                    nav: false,
                    dots: true,
                    autoplay: false,
                    responsive: {
                        0: {
                            items: 4
                        },
                        600: {
                            items: 4
                        },
                        1000: {
                            items: 6
                        }
                    }
                });

                // Initialize zoom modal slider
                $('#image_zoom_modal_slider').owlCarousel({
                    loop: false,
                    margin: 10,
                    nav: true,
                    dots: false,
                    autoplay: false,
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

                // Thumbnail click handler
                $('.item').click(function() {
                    var imgSrc = $(this).find('img').attr('src');
                    $('.enlarged_image').attr('src', imgSrc);

                    // Add active class
                    $('#product_image_slider .item').removeClass('active');
                    $(this).addClass('active');
                });

                // Enlarged image click for zoom modal
                $('.enlarged_image').click(function() {
                    let main_image = $(this).attr('src');
                    $('#image_zoom_modal .zoom_image').attr('src', main_image);
                    $('#image_zoom_modal').modal('show');
                });

                // Initialize related products carousel
                $('#related_product_carousel').owlCarousel({
                    loop: false,
                    margin: 20,
                    nav: true,
                    autoplay: true,
                    autoplayTimeout: 4000,
                    autoplayHoverPause: true,
                    responsive: {
                        0: {
                            items: 1
                        },
                        600: {
                            items: 2
                        },
                        1000: {
                            items: 4
                        },
                        1200: {
                            items: 5
                        }
                    }
                });

                // Initialize media carousels
                $(".media-carousel").each(function(index) {
                    let id = $(this).attr('data-carousel-id');
                    $('#' + id).owlCarousel({
                        loop: false,
                        nav: true,
                        margin: 10,
                        autoplay: false,
                        autoplayTimeout: 4000,
                        autoplayHoverPause: true,
                        items: 1,
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
                });

                // Add loading animation to buttons
                $('.add-to-cart, .add-to-wishlist').click(function(e) {
                    $(this).addClass('loading');
                    setTimeout(() => {
                        $(this).removeClass('loading');
                    }, 1000);
                });
            });
        </script>

        <script>
            function magnify(imgID, zoom) {
                const existing = document.querySelector('.img-magnifier-glass');
                if (existing) {
                    existing.remove();
                }

                var img, glass, w, h, bw;
                img = document.getElementById(imgID);

                glass = document.createElement("DIV");
                glass.setAttribute("class", "img-magnifier-glass");

                img.parentElement.insertBefore(glass, img);

                glass.style.backgroundImage = "url('" + img.src + "')";
                glass.style.backgroundRepeat = "no-repeat";
                glass.style.backgroundSize = (img.width * zoom) + "px " + (img.height * zoom) + "px";
                bw = 3;
                w = glass.offsetWidth / 2;
                h = glass.offsetHeight / 2;

                glass.addEventListener("mousemove", moveMagnifier);
                img.addEventListener("mousemove", moveMagnifier);

                glass.addEventListener("touchmove", moveMagnifier);
                img.addEventListener("touchmove", moveMagnifier);

                function moveMagnifier(e) {
                    var pos, x, y;
                    e.preventDefault();
                    pos = getCursorPos(e);
                    x = pos.x;
                    y = pos.y;
                    if (x < 0 || x > img.width || y < 0 || y > img.height) {
                        glass.remove();
                        return;
                    }
                    if (x > img.width - (w / zoom)) {
                        x = img.width - (w / zoom);
                    }
                    if (x < w / zoom) {
                        x = w / zoom;
                    }
                    if (y > img.height - (h / zoom)) {
                        y = img.height - (h / zoom);
                    }
                    if (y < h / zoom) {
                        y = h / zoom;
                    }
                    glass.style.left = (x - w) + "px";
                    glass.style.top = (y - h) + "px";
                    glass.style.backgroundPosition = "-" + ((x * zoom) - w + bw) + "px -" + ((y * zoom) - h + bw) + "px";
                }

                function getCursorPos(e) {
                    var a, x = 0,
                        y = 0;
                    e = e || window.event;
                    a = img.getBoundingClientRect();
                    x = e.pageX - a.left;
                    y = e.pageY - a.top;
                    x = x - window.pageXOffset;
                    y = y - window.pageYOffset;
                    return {
                        x: x,
                        y: y
                    };
                }
            }

            // Initialize magnifier if zoom_image exists
            if (document.getElementById('zoom_image')) {
                document.getElementById('zoom_image').addEventListener('mouseenter', function() {
                    magnify("zoom_image", 3);
                });
            }
        </script>
    @endpush
@endsection

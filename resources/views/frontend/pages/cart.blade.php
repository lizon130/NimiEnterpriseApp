@extends('frontend.layout.app')

@section('content')

    <style>
        /* =========================================================
       CART PAGE
       IMPORTANT:
       Do not override Bootstrap .container width.
    ========================================================= */

        #wishlist {
            width: 100%;
            overflow-x: clip;
        }

        /* -----------------------------------------
       CART TABLE BOX
    ------------------------------------------ */
        .cart-table-box {
            width: 100%;
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
        }

        /* Only this area can scroll */
        .cart-table-scroll {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
        }

        /* Desktop table remains original */
        .cart-main-table {
            width: 100%;
            margin-bottom: 0 !important;
            table-layout: auto;
        }

        .cart-main-table th {
            vertical-align: middle;
            white-space: nowrap;
        }

        .cart-main-table td {
            vertical-align: middle;
        }

        .cart-product-name {
            min-width: 180px;
        }

        /* -----------------------------------------
       QUANTITY
    ------------------------------------------ */
        .cart__card__request--count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            white-space: nowrap;
        }

        .cart__card__request--count .wishlist__card--sign {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .cart__card__request--count .wishlist__card--number {
            min-width: 22px;
            text-align: center;
        }

        /* -----------------------------------------
       SUBTOTAL
    ------------------------------------------ */
        .cart-summary {
            width: 100%;
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
            margin-bottom: 22px;
        }

        .cart-summary-card {
            min-width: 280px;
            background: #f8f9fa;
            border: 1px solid #e8eaed;
            border-radius: 8px;
            padding: 15px 18px;
        }

        .cart-summary-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
        }

        .cart-summary-label {
            margin: 0;
            font-size: 15px;
            font-weight: 500;
        }

        .cart-summary-price {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
        }

        /* -----------------------------------------
       FOOTER BUTTONS
    ------------------------------------------ */
        .cart-footer-actions {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        /* -----------------------------------------
       MOBILE SCROLL INDICATOR
    ------------------------------------------ */
        .mobile-scroll-hint {
            display: none;
        }

        /* =========================================================
       TABLET + MOBILE
    ========================================================= */
        @media (max-width: 767.98px) {

            #wishlist {
                overflow-x: hidden;
            }

            #wishlist .page_title {
                font-size: 23px;
                margin-bottom: 15px;
            }

            .cart-table-box {
                border-radius: 10px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            }

            /*
           Table stays wider than mobile screen.
           ONLY .cart-table-scroll moves horizontally.
        */
            .cart-main-table {
                width: 820px;
                min-width: 820px;
                font-size: 13px;
            }

            .cart-main-table th {
                font-size: 12px;
                padding: 10px 9px;
                background: #f7f8fa;
            }

            .cart-main-table td {
                padding: 11px 9px;
            }

            .cart-product-name {
                min-width: 185px;
                max-width: 210px;
                white-space: normal !important;
                line-height: 1.4;
            }

            .cart-main-table th:nth-child(1),
            .cart-main-table td:nth-child(1) {
                min-width: 45px;
                text-align: center;
            }

            .cart-main-table th:nth-child(3),
            .cart-main-table td:nth-child(3) {
                min-width: 85px;
            }

            .cart-main-table th:nth-child(4),
            .cart-main-table td:nth-child(4) {
                min-width: 100px;
            }

            .cart-main-table th:nth-child(5),
            .cart-main-table td:nth-child(5) {
                min-width: 125px;
            }

            .cart-main-table th:nth-child(6),
            .cart-main-table td:nth-child(6),
            .cart-main-table th:nth-child(7),
            .cart-main-table td:nth-child(7) {
                min-width: 115px;
            }

            .cart-main-table th:nth-child(8),
            .cart-main-table td:nth-child(8) {
                min-width: 55px;
            }

            /* Better quantity buttons on touch device */
            .cart__card__request--count {
                gap: 5px;
            }

            .cart__card__request--count .wishlist__card--sign {
                width: 28px;
                height: 28px;
                border-radius: 6px;
            }

            .cart__card__request--count .wishlist__card--number {
                min-width: 24px;
                font-size: 13px;
                font-weight: 600;
            }

            /* Scroll hint */
            .mobile-scroll-hint {
                display: flex;
                align-items: center;
                gap: 7px;
                padding: 8px 12px;
                font-size: 11px;
                color: #777;
                background: #fafafa;
                border-bottom: 1px solid #eeeeee;
            }

            .mobile-scroll-hint i {
                font-size: 11px;
            }

            /* Scrollbar */
            .cart-table-scroll::-webkit-scrollbar {
                height: 5px;
            }

            .cart-table-scroll::-webkit-scrollbar-track {
                background: #f2f2f2;
            }

            .cart-table-scroll::-webkit-scrollbar-thumb {
                background: #b5b5b5;
                border-radius: 20px;
            }

            /* Subtotal */
            .cart-summary {
                margin-top: 16px;
                margin-bottom: 16px;
            }

            .cart-summary-card {
                width: 100%;
                min-width: 0;
                padding: 14px 15px;
                border-radius: 10px;
            }

            .cart-summary-label {
                font-size: 14px;
            }

            .cart-summary-price {
                font-size: 18px;
            }

            /* Buttons look better like app */
            .cart-footer-actions {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }

            .cart-footer-actions .btn__secondary,
            .cart-footer-actions .btn__primary {
                width: 100%;
                min-height: 45px;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
                border-radius: 7px;
            }

            .wish-alert {
                text-align: center;
                margin-top: 20px;
            }

            .wish-margin .btn__secondary {
                width: 100%;
                max-width: 320px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
        }

        /* =========================================================
       SMALL MOBILE
    ========================================================= */
        @media (max-width: 480px) {

            #wishlist .page_title {
                font-size: 21px;
            }

            .cart-main-table {
                width: 790px;
                min-width: 790px;
                font-size: 12px;
            }

            .cart-main-table th {
                padding: 9px 7px;
            }

            .cart-main-table td {
                padding: 10px 7px;
            }

            .cart-product-name {
                min-width: 170px;
                max-width: 190px;
            }
        }
    </style>


    <div id="wishlist">

        {{-- Breadcrumb --}}
        <div class="breadcrumb__nk">
            <div class="container">
                <a href="{{ route('home') }}" class="text-light">
                    {{ trans('language.home') }}
                </a>
                /
                {{ trans('language.cart') }}
            </div>
        </div>


        {{-- Go Back --}}
        <div class="container go_back_container">
            <a href="{{ route('products') }}">
                <i class="fa-solid fa-angle-left"></i>
                {{ trans('language.go_back') }}
            </a>
        </div>


        <div class="container">

            <h1 class="page_title text-start">
                {{ trans('language.cart') }}
            </h1>


            <div class="cart__table pb-4">

                {{-- Success Message --}}
                @if (session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong>
                        {{ session()->get('message') }}

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                    </div>
                @endif


                {{-- Error Message --}}
                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong>
                        {{ session()->get('error') }}

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                    </div>
                @endif


                @if (count($carts) > 0)

                    @php
                        $total_price = 0;
                    @endphp


                    {{-- =====================================================
                     TABLE BOX
                ====================================================== --}}
                    <div class="cart-table-box">

                        {{-- Mobile Only --}}
                        <div class="mobile-scroll-hint">
                            <i class="fa-solid fa-left-right"></i>
                            Swipe left or right to view all cart information
                        </div>


                        {{-- ONLY THIS DIV SCROLLS --}}
                        <div class="cart-table-scroll">

                            <table class="table table-bordered cart-main-table">

                                <thead class="table-light">
                                    <tr>
                                        <th>No.</th>

                                        <th>
                                            Product / Part Name
                                        </th>

                                        <th>
                                            Type
                                        </th>

                                        <th>
                                            Code
                                        </th>

                                        <th>
                                            Unit
                                        </th>

                                        <th class="text-center">
                                            Unit Price
                                        </th>

                                        <th class="text-center">
                                            Total Price
                                        </th>

                                        <th></th>
                                    </tr>
                                </thead>


                                <tbody>

                                    @forelse ($carts as $cart)
                                        {{-- PRODUCT --}}
                                        @if ($cart['type'] == 'product')
                                            @php
                                                $product = App\Models\Product::find($cart['product']['id']);

                                                if ($product) {
                                                    $unit_price = Helper::priceAfterOffer($product->id);
                                                    $total_price = $total_price + $cart['quantity'] * $unit_price;
                                                }
                                            @endphp


                                            @if ($product)
                                                <tr>

                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>


                                                    <td class="cart-product-name">
                                                        {{ $product->getTranslation(Session::get('language') ?? 'en', 'name') ?? ($product->name ?? '') }}
                                                    </td>


                                                    <td>
                                                        {{ $cart['type'] ?? '' }}
                                                    </td>


                                                    <td>
                                                        {{ $product->code ?? '' }}
                                                    </td>


                                                    <td>
                                                        <div class="cart__card__request--count">

                                                            <a href="{{ route('decrement.from.cart', $cart['product']['id']) }}"
                                                                class="wishlist__card--sign" aria-label="Decrease quantity">

                                                                <i class="fa-solid fa-minus"></i>

                                                            </a>


                                                            <span class="wishlist__card--number">
                                                                {{ $cart['quantity'] ?? 0 }}
                                                            </span>


                                                            <a href="{{ route('increment.from.cart', $cart['product']['id']) }}"
                                                                class="wishlist__card--sign" aria-label="Increase quantity">

                                                                <i class="fa-solid fa-plus"></i>

                                                            </a>

                                                        </div>
                                                    </td>


                                                    <td class="text-center">

                                                        <div>
                                                            ৳{{ number_format(Helper::priceAfterOffer($product->id), 2) }}
                                                        </div>

                                                        @if ($product->discount)
                                                            <small>
                                                                <del>
                                                                    ৳{{ number_format($product->price, 2) }}
                                                                </del>
                                                            </small>
                                                        @endif

                                                    </td>


                                                    <td class="text-center">
                                                        <strong>
                                                            ৳{{ number_format($cart['quantity'] * Helper::priceAfterOffer($product->id), 2) }}
                                                        </strong>
                                                    </td>


                                                    <td class="text-center">

                                                        <a href="{{ route('remove.from.cart', $cart['product']['id']) }}"
                                                            class="text-danger" title="Remove from cart">

                                                            <i class="fa-solid fa-trash"></i>

                                                        </a>

                                                    </td>

                                                </tr>
                                            @endif


                                            {{-- PRODUCT PART --}}
                                        @else
                                            @php
                                                $part = App\Models\ProductPart::find($cart['product']['id']);

                                                if ($part) {
                                                    $unit_price_part = Helper::partPriceFaterOffer($part->id);
                                                    $total_price = $total_price + $cart['quantity'] * $unit_price_part;
                                                }
                                            @endphp


                                            @if ($part)
                                                <tr>

                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>


                                                    <td class="cart-product-name">
                                                        {{ $part->getTranslation(Session::get('language') ?? 'en', 'name') ?? ($part->name ?? '') }}
                                                    </td>


                                                    <td>
                                                        {{ $cart['type'] ?? '' }}
                                                    </td>


                                                    <td>
                                                        {{ $part->code ?? '' }}
                                                    </td>


                                                    <td>
                                                        <div class="cart__card__request--count">

                                                            <a href="{{ route('decrement.from.cart', $cart['product']['id']) }}"
                                                                class="wishlist__card--sign" aria-label="Decrease quantity">

                                                                <i class="fa-solid fa-minus"></i>

                                                            </a>


                                                            <span class="wishlist__card--number">
                                                                {{ $cart['quantity'] ?? 0 }}
                                                            </span>


                                                            <a href="{{ route('increment.from.cart', $cart['product']['id']) }}"
                                                                class="wishlist__card--sign" aria-label="Increase quantity">

                                                                <i class="fa-solid fa-plus"></i>

                                                            </a>

                                                        </div>
                                                    </td>


                                                    <td class="text-center">

                                                        <div>
                                                            ৳{{ number_format(Helper::partPriceFaterOffer($part->id), 2) }}
                                                        </div>

                                                        @if ($part->discount)
                                                            <small>
                                                                <del>
                                                                    ৳{{ number_format($part->price, 2) }}
                                                                </del>
                                                            </small>
                                                        @endif

                                                    </td>


                                                    <td class="text-center">
                                                        <strong>
                                                            ৳{{ number_format($cart['quantity'] * Helper::partPriceFaterOffer($part->id), 2) }}
                                                        </strong>
                                                    </td>


                                                    <td class="text-center">

                                                        <a href="{{ route('remove.from.cart', $cart['product']['id']) }}"
                                                            class="text-danger" title="Remove from cart">

                                                            <i class="fa-solid fa-trash"></i>

                                                        </a>

                                                    </td>

                                                </tr>
                                            @endif
                                        @endif

                                    @empty
                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>
                    {{-- END TABLE BOX --}}


                    {{-- =====================================================
                     SUBTOTAL
                     OUTSIDE SCROLL AREA
                ====================================================== --}}
                    <div class="cart-summary">

                        <div class="cart-summary-card">

                            <div class="cart-summary-row">

                                <h6 class="cart-summary-label">
                                    Subtotal
                                </h6>

                                <h5 class="cart-summary-price">
                                    ৳{{ number_format($total_price, 2) }}
                                </h5>

                            </div>

                        </div>

                    </div>


                    {{-- =====================================================
                     ACTION BUTTONS
                ====================================================== --}}
                    <div class="cart-footer-actions">

                        <a href="{{ route('products') }}" class="btn__secondary">

                            {{ trans('language.btn_continue_shipping') }}

                        </a>


                        @if (auth()->user())
                            @if (auth()->user()->role == 1)
                                <a href="{{ route('login') }}" class="btn__primary">

                                    Please Login!

                                </a>
                            @else
                                <a href="{{ route('order') }}" class="btn__primary">

                                    {{ trans('language.btn_confirm_purchase') }}

                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn__primary">

                                {{ trans('language.btn_confirm_purchase') }}

                            </a>
                        @endif

                    </div>
                @else
                    {{ Session::get('session_id') }}


                    <p class="wish-alert">
                        {{ trans('language.no_product_on_cart') }}!
                    </p>


                    <div class="col-lg-12 text-center wish-margin">

                        <a class="btn__secondary" href="{{ route('products') }}">

                            Choose Your Product

                        </a>

                    </div>

                @endif

            </div>

        </div>

    </div>

@endsection

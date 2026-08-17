@extends('frontend.layout.app')

@push('header')
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        a {
            text-decoration: none;
        }

        img {
            max-width: 100%;
        }

        :root {
            --primary: #f85606;
            --primary-dark: #d94a04;
            --dark: #111827;
            --muted: #6b7280;
        }

        /* ================= COMMON ================= */
        .section-heading {
            text-align: center;
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0;
            letter-spacing: -0.03em;
        }

        .section-heading-underline {
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), #ff9d3d);
            margin: 1rem auto 2rem;
            border-radius: 50px;
        }

        .section-heading-underline.white {
            background: linear-gradient(90deg, #fff, #ffd2b8);
        }

        /* ================= HERO ================= */
        #hero {
            width: 100%;
            overflow: hidden;
            padding: 0 !important;
            margin: 0 !important;
            background: #000;
        }

        .home-carousel,
        .home-carousel .carousel-inner,
        .home-carousel .carousel-item {
            width: 100%;
        }

        .home-carousel .carousel-item {
            position: relative;
            height: 82vh;
            min-height: 600px;
            background: #000;
        }

        .home-carousel .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        /* Overlay */
        .home-carousel .carousel-item::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: 1;
            background: linear-gradient(90deg,
                    rgba(0, 0, 0, .75),
                    rgba(0, 0, 0, .38),
                    rgba(0, 0, 0, .12));
        }

        .home-banner-inner {
            position: absolute;
            z-index: 3;
            top: 50%;
            left: 8%;
            transform: translateY(-50%);
            max-width: 600px;
            width: 88%;
            padding: 2.2rem;
            border-radius: 26px;
            background: rgba(0, 0, 0, .52);
            border: 1px solid rgba(255, 255, 255, .18);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            color: #fff;
        }

        .home-banner-inner .inner-header {
            font-size: 2.7rem;
            font-weight: 900;
            line-height: 1.15;
            margin-bottom: .8rem;
        }

        .home-banner-inner .inner-pragraph {
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 1.3rem;
        }

        .button-area {
            display: block !important;
            width: auto;
            visibility: visible !important;
            opacity: 1 !important;
        }

        .btn-slider {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            min-height: 50px;
            padding: 12px 34px;
            border-radius: 999px;
            font-weight: 900;
            font-size: .95rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #fff !important;
            background: #f85606 !important;
            box-shadow: 0 14px 30px rgba(248, 86, 6, .45);
            border: 0;
            text-decoration: none;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative;
            z-index: 10;
        }

        .btn-slider:hover {
            background: #d94a04 !important;
            transform: translateY(-2px);
        }

        .carousel-indicators {
            z-index: 5;
            bottom: 24px;
        }

        .carousel-indicators button {
            width: 9px !important;
            height: 9px !important;
            border-radius: 50% !important;
            background: rgba(255, 255, 255, .7) !important;
        }

        .carousel-indicators button.active {
            width: 25px !important;
            border-radius: 50px !important;
            background: #f85606 !important;
        }

        .carousel-control-prev,
        .carousel-control-next {
            z-index: 4;
            width: 7%;
        }

        /* ================= MOBILE HERO FIX ================= */
        @media (max-width: 768px) {
            #hero {
                height: auto !important;
                min-height: auto !important;
                overflow: hidden !important;
            }

            .home-carousel .carousel-item {
                height: 560px !important;
                min-height: 560px !important;
                max-height: 560px !important;
            }

            .home-carousel .carousel-item img {
                height: 560px !important;
                object-fit: cover !important;
                object-position: center top !important;
            }

            .home-carousel .carousel-item::before {
                background: linear-gradient(180deg,
                        rgba(0, 0, 0, .08) 0%,
                        rgba(0, 0, 0, .35) 40%,
                        rgba(0, 0, 0, .90) 100%) !important;
            }

            .home-banner-inner {
                top: auto !important;
                left: 14px !important;
                right: 14px !important;
                bottom: 130px !important;
                transform: none !important;
                width: auto !important;
                max-width: none !important;
                padding: 107px 25px !important;
                border-radius: 18px !important;
                text-align: center !important;
                background: rgba(0, 0, 0, .72) !important;
                border: 1px solid rgba(255, 255, 255, .20) !important;
                z-index: 20 !important;
                visibility: visible !important;
                opacity: 1 !important;
            }

            .home-banner-inner .inner-header {
                font-size: 1.18rem !important;
                line-height: 1.25 !important;
                margin-bottom: 6px !important;
            }

            .home-banner-inner .inner-pragraph {
                font-size: .72rem !important;
                line-height: 1.4 !important;
                margin-bottom: 11px !important;
                display: -webkit-box !important;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .button-area {
                display: block !important;
                width: 100% !important;
                visibility: visible !important;
                opacity: 1 !important;
                margin-top: 4px !important;
            }

            .btn-slider {
                display: flex !important;
                width: 100% !important;
                min-height: 48px !important;
                align-items: center !important;
                justify-content: center !important;
                padding: 10px 14px !important;
                border-radius: 14px !important;
                font-size: .8rem !important;
                font-weight: 900 !important;
                line-height: 1.2 !important;
                text-align: center !important;
                background: #f85606 !important;
                color: #ffffff !important;
                visibility: visible !important;
                opacity: 1 !important;
                position: relative !important;
                z-index: 999 !important;
                box-shadow:
                    0 0 0 4px rgba(248, 86, 6, .20),
                    0 12px 26px rgba(248, 86, 6, .50) !important;
            }

            .carousel-control-prev,
            .carousel-control-next {
                display: none !important;
            }

            .carousel-indicators {
                bottom: 24px !important;
                z-index: 6 !important;
            }
        }

        /* ================= VERY SMALL MOBILE ================= */
        @media (max-width: 420px) {

            .home-carousel .carousel-item,
            .home-carousel .carousel-item img {
                height: 520px !important;
                min-height: 520px !important;
                max-height: 520px !important;
            }

            .home-banner-inner {
                bottom: 64px !important;
                padding: 13px 11px !important;
            }

            .home-banner-inner .inner-header {
                font-size: 1.05rem !important;
            }

            .home-banner-inner .inner-pragraph {
                font-size: .68rem !important;
                margin-bottom: 9px !important;
            }

            .btn-slider {
                min-height: 45px !important;
                font-size: .72rem !important;
            }
        }

        /* ================= PARTNERS ================= */
        #partners {
            padding: 4rem 1rem;
            text-align: center;
            background: linear-gradient(135deg, #f85606, #ff8a00);
        }

        #partners .section-heading {
            color: #fff;
        }

        .partners-img-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 1.4rem;
        }

        .partner-logo-area {
            width: 135px;
            padding: 1rem;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, .16);
            transition: all .25s ease;
        }

        .partner-logo-area:hover {
            transform: translateY(-6px);
        }

        .partner-logo-area img {
            width: 100%;
            height: 72px;
            object-fit: contain;
        }

        /* ================= PRODUCT SECTIONS ================= */
        .home__products__section {
            padding: 4rem 1rem;
            background: #fff;
        }

        #product-category {
            padding: 4rem 1rem;
            background: linear-gradient(180deg, #fff7f2, #ffffff);
        }

        .product-cart,
        #product-category .card {
            height: 100%;
            border: none;
            border-radius: 22px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 12px 30px rgba(17, 24, 39, .08);
            transition: all .25s ease;
        }

        .product-cart:hover,
        #product-category .card:hover {
            transform: translateY(-7px);
            box-shadow: 0 22px 42px rgba(17, 24, 39, .14);
        }

        .product-cart img {
            width: 100%;
            height: 230px;
            object-fit: contain;
            padding: 1rem;
            background: #fafafa;
        }

        .product-title {
            min-height: 100px;
            padding: 1rem;
            margin: 0;
            color: var(--dark);
            font-weight: 800;
            border-top: 1px solid #f1f1f1;
            gap: .25rem;
        }

        .product-title small {
            font-weight: 500;
            color: var(--muted);
            font-size: .75rem;
        }

        #product-category .card img {
            width: 100%;
            height: 190px;
            object-fit: cover;
        }

        #product-category .card p {
            padding: 1rem;
            margin: 0;
            color: var(--dark);
            font-weight: 800;
            text-align: center;
        }

        /* ================= OWL ================= */
        .owl-carousel .owl-stage {
            display: flex;
        }

        .owl-carousel .owl-item {
            display: flex;
        }

        .owl-carousel .item {
            width: 100%;
            height: 100%;
        }

        .owl-carousel .owl-nav button.owl-prev,
        .owl-carousel .owl-nav button.owl-next {
            position: absolute;
            top: 45%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            border-radius: 50% !important;
            background: #fff !important;
            color: var(--primary) !important;
            box-shadow: 0 10px 24px rgba(0, 0, 0, .18);
            font-size: 25px !important;
            line-height: 1 !important;
        }

        .owl-carousel .owl-nav button.owl-prev {
            left: -12px;
        }

        .owl-carousel .owl-nav button.owl-next {
            right: -12px;
        }

        .owl-dots {
            margin-top: 1.2rem;
        }

        .owl-dot span {
            background: #ffd4bd !important;
        }

        .owl-dot.active span {
            background: var(--primary) !important;
        }

        @media (max-width: 768px) {
            .section-heading {
                font-size: 1.45rem;
            }

            .section-heading-underline {
                width: 55px;
                height: 3px;
                margin: .7rem auto 1.5rem;
            }

            #partners,
            .home__products__section,
            #product-category {
                padding: 2.7rem .75rem;
            }

            .partners-img-container {
                gap: .8rem;
            }

            .partner-logo-area {
                width: calc(50% - .8rem);
                max-width: 145px;
                padding: .8rem;
                border-radius: 16px;
            }

            .partner-logo-area img {
                height: 55px;
            }

            .product-cart,
            #product-category .card {
                border-radius: 17px;
            }

            .product-cart img {
                height: 165px;
                padding: .75rem;
            }

            .product-title {
                min-height: 88px;
                padding: .75rem;
                font-size: .78rem;
            }

            .product-title small {
                font-size: .66rem;
            }

            #product-category .card img {
                height: 145px;
            }

            #product-category .card p {
                padding: .75rem;
                font-size: .78rem;
            }

            .owl-carousel .owl-nav {
                display: none;
            }
        }
    </style>
@endpush

@section('content')
    <section id="hero" class="d-flex align-items-center">
        <div id="carouselExampleAutoplaying" class="home-carousel carousel slide w-100" data-pause="false"
            data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach ($banners as $banner)
                    <button type="button" data-bs-target="#carouselExampleAutoplaying"
                        data-bs-slide-to="{{ $loop->iteration - 1 }}"
                        class="@if ($loop->iteration == 1) active @endif"
                        aria-current="{{ $loop->iteration == 1 ? 'true' : 'false' }}"
                        aria-label="Slide-{{ $loop->iteration }}">
                    </button>
                @endforeach
            </div>

            <div class="carousel-inner">
                @foreach ($banners as $banner)
                    <div class="carousel-item @if ($loop->iteration == 1) active @endif">
                        <img src="{{ asset('uploads/resource-images/' . $banner->image) }}" class="d-block w-100"
                            alt="{{ $banner->title ?? 'Banner Image' }}" loading="eager">

                        <div class="home-banner-inner">
                            <h4 class="inner-header" style="color:{{ $banner->title_color ?? '#ffffff' }}">
                                {{ $banner->title }}
                            </h4>

                            <p class="inner-pragraph" style="color: {{ $banner->details_color ?? '#f1f5f9' }}">
                                {{ $banner->details }}
                            </p>

                            @if (!empty($banner->button_text))
                                <div class="button-area">
                                    <a href="{{ route('products') }}" class="btn btn-slider"
                                        style="background-color: {{ $banner->button_color ?? '#f85606' }} !important; color: #ffffff !important;">
                                        Products
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <section id="partners">
        <h1 class="section-heading text-light">{{ trans('language.proud_partners') }}</h1>
        <div class="section-heading-underline white"></div>

        <div class="partners-img-container container">
            @foreach ($partners as $partner)
                <div class="partner-logo-area">
                    <a href="{{ route('brand.products', $partner->slug) }}" title="{{ $partner->title }}">
                        <img class="img-fluid" src="{{ asset('uploads/brand-images/' . $partner->image) }}"
                            alt="{{ $partner->title }}" loading="lazy">
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <section class="home__products__section pt-0">
        <h1 class="section-heading text-center d-block mt-4">{{ trans('language.featured_products') }}</h1>
        <div class="section-heading-underline"></div>

        <div class="container">
            <div class="owl-carousel owl-theme" id="feature_product_carousel">
                @foreach ($products as $product)
                    <div class="item h-100">
                        <a href="{{ url('product/' . $product->slug) }}">
                            <div class="card product-cart h-100">
                                <img src="{{ asset('uploads/product-images/' . $product->thumbnail) }}"
                                    alt="{{ $product->name }}" loading="lazy">

                                <p title="{{ $product->name }}"
                                    class="product-title text-uppercase text-center d-flex flex-column">
                                    <span>
                                        {{ Str::limit($product->getTranslation(Session::get('language') ?? 'en', 'name') ?? $product->name, 25, '...') }}
                                    </span>

                                    <small>Item Code: {{ $product->code }}</small>

                                    @php
                                        $feature_product_attributes = Cache::remember(
                                            "feature_product_attributes_{$product->id}",
                                            now()->addHours(1),
                                            function () use ($product) {
                                                return $product->attributes;
                                            },
                                        );
                                    @endphp

                                    @if (count($feature_product_attributes) > 0)
                                        <small class="text-capitalize">
                                            @foreach ($feature_product_attributes as $attribute)
                                                @if ($attribute->is_filter == 1)
                                                    {{ $attribute->attribute_name }}: {{ $attribute->value }},
                                                @endif
                                            @endforeach
                                        </small>
                                    @endif
                                </p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="product-category">
    <h1 class="section-heading text-center d-block mt-4">
        {{ trans('language.product_category') }}
    </h1>
    <div class="section-heading-underline"></div>

    <div class="product-category__card-container container">
        <div class="owl-carousel owl-theme" id="feature_category_carousel">
            @foreach ($categories as $category)
                @php
                    $categoryImage = !empty($category->image) && file_exists(public_path('uploads/category-images/' . $category->image))
                        ? asset('uploads/category-images/' . $category->image)
                        : asset('assets/img/medicine.png');
                @endphp

                <div class="item">
                    <div class="card">
                        <a href="{{ url('category/' . $category->slug) }}">
                            <img src="{{ $categoryImage }}"
                                alt="{{ $category->getTranslation(Session::get('language') ?? 'en', 'title') }}"
                                loading="lazy">

                            <p class="text-uppercase">
                                {{ $category->getTranslation(Session::get('language') ?? 'en', 'title') }}
                            </p>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

    @push('footer')
        <script type="text/javascript">
            $(document).ready(function() {
                $('#feature_product_carousel').owlCarousel({
                    loop: true,
                    nav: true,
                    dots: true,
                    margin: 18,
                    autoplay: true,
                    autoplayTimeout: 4000,
                    autoplayHoverPause: true,
                    responsive: {
                        0: {
                            items: 2,
                            margin: 10,
                            nav: false
                        },
                        480: {
                            items: 2,
                            margin: 12,
                            nav: false
                        },
                        576: {
                            items: 2,
                            margin: 14,
                            nav: false
                        },
                        768: {
                            items: 3,
                            margin: 16
                        },
                        992: {
                            items: 4,
                            margin: 18
                        },
                        1200: {
                            items: 5,
                            margin: 20
                        }
                    }
                });

                $('#feature_category_carousel').owlCarousel({
                    loop: true,
                    nav: true,
                    dots: true,
                    margin: 18,
                    autoplay: true,
                    autoplayTimeout: 4000,
                    autoplayHoverPause: true,
                    responsive: {
                        0: {
                            items: 2,
                            margin: 10,
                            nav: false
                        },
                        480: {
                            items: 2,
                            margin: 12,
                            nav: false
                        },
                        576: {
                            items: 2,
                            margin: 14,
                            nav: false
                        },
                        768: {
                            items: 3,
                            margin: 16
                        },
                        992: {
                            items: 4,
                            margin: 18
                        },
                        1200: {
                            items: 5,
                            margin: 20
                        }
                    }
                });

                $('#feature_service_carousel').owlCarousel({
                    loop: true,
                    nav: true,
                    margin: 20,
                    autoplay: true,
                    autoplayTimeout: 4000,
                    autoplayHoverPause: true,
                    responsive: {
                        0: {
                            items: 1,
                            margin: 10
                        },
                        480: {
                            items: 2,
                            margin: 15
                        },
                        576: {
                            items: 2,
                            margin: 15
                        },
                        768: {
                            items: 2,
                            margin: 15
                        },
                        992: {
                            items: 3,
                            margin: 20
                        },
                        1200: {
                            items: 4,
                            margin: 20
                        }
                    }
                });

                $('#feature_news_carousel').owlCarousel({
                    loop: true,
                    nav: true,
                    margin: 20,
                    autoplay: false,
                    autoplayTimeout: 4000,
                    autoplayHoverPause: true,
                    responsive: {
                        0: {
                            items: 1,
                            margin: 10
                        },
                        480: {
                            items: 1,
                            margin: 10
                        },
                        576: {
                            items: 1,
                            margin: 10
                        },
                        768: {
                            items: 2,
                            margin: 15
                        },
                        992: {
                            items: 3,
                            margin: 20
                        },
                        1200: {
                            items: 4,
                            margin: 20
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
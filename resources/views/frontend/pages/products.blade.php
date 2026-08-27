@extends('frontend.layout.app')

@section('content')
<style>
    :root {
        --primary: #f85606;
        --primary-dark: #d94a04;
        --primary-soft: #fff3ec;
        --dark: #111827;
        --muted: #6b7280;
        --border: #e5e7eb;
        --card: #ffffff;
        --bg: #f8fafc;
    }

    #products {
        background: var(--bg);
        min-height: 100vh;
        padding-bottom: 50px;
    }

    .breadcrumb__nk {
        background: linear-gradient(135deg, var(--primary), #ff8a00);
        padding: 18px 0;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
    }

    .breadcrumb__nk a {
        text-decoration: none;
        color: #fff;
        opacity: .95;
    }

    .products_title_container {
        padding: 35px 15px 25px;
        text-align: center;
    }

    .page_title {
        font-size: 34px;
        font-weight: 800;
        color: var(--dark);
        margin: 0;
        position: relative;
        display: inline-block;
    }

    .page_title::after {
        content: "";
        width: 70px;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), #ff9d3d);
        border-radius: 20px;
        position: absolute;
        left: 50%;
        bottom: -12px;
        transform: translateX(-50%);
    }

    .products__form {
        display: flex !important;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
        background: #fff;
        border-radius: 18px;
        padding: 16px;
        box-shadow: 0 12px 35px rgba(17, 24, 39, .08);
        margin-bottom: 22px;
        width: 100%;
    }

    .products__form .form-floating {
        margin-bottom: 0 !important;
        flex: 1 1 45%;
        min-width: 220px;
    }

    .products__form button {
        flex: 1 1 150px;
    }

    .products__form .form-control {
        border-radius: 14px;
        border: 1px solid var(--border);
        min-height: 56px;
        font-size: 14px;
        box-shadow: none;
    }

    .products__form .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(248, 86, 6, .12);
    }

    .products__form label {
        color: var(--muted);
        font-size: 14px;
    }

    .products__form button {
        background: var(--primary);
        color: #fff;
        border-radius: 14px;
        min-height: 56px;
        font-weight: 800;
        border: none;
        transition: .25s;
    }

    .products__form button:hover {
        background: var(--primary-dark);
        color: #fff;
        transform: translateY(-1px);
    }

    .products__menu {
        background: #fff;
        border-radius: 22px;
        padding: 20px;
        box-shadow: 0 12px 35px rgba(17, 24, 39, .08);
        height: fit-content;
        position: sticky;
        top: 90px;
    }

    .filter__text {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 20px;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 18px;
    }

    .filter__text::before {
        content: "\f0b0";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        color: var(--primary);
        font-size: 17px;
    }

    .accordion-item {
        border: 1px solid var(--border);
        border-radius: 16px !important;
        overflow: hidden;
        background: #fff;
    }

    .accordion-button {
        font-weight: 700;
        color: var(--dark);
        background: #fff;
        border-radius: 16px !important;
        box-shadow: none !important;
        padding: 15px 16px;
    }

    .accordion-button:not(.collapsed) {
        background: var(--primary-soft);
        color: var(--primary);
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: transparent;
    }

    .accordion-body {
        padding: 14px 16px;
        max-height: 260px;
        overflow-y: auto;
    }

    .form-check {
        padding: 8px 0 8px 1.7em;
        margin: 0;
    }

    .form-check-input {
        cursor: pointer;
        border-color: #cbd5e1;
    }

    .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .form-check-label {
        cursor: pointer;
        color: #374151;
        font-size: 14px;
        font-weight: 500;
    }

    #productListing {
        background: #fff;
        border-radius: 22px;
        min-height: 300px;
        padding: 18px !important;
        box-shadow: 0 12px 35px rgba(17, 24, 39, .08);
        transition: opacity .15s ease;
    }

    #productListing.listing-loading {
        opacity: .45;
        pointer-events: none;
    }

    .product-pagination {
        padding-bottom: 20px;
    }

    .pagination {
        gap: 6px;
        flex-wrap: wrap;
    }

    .pagination .page-link,
    .pagination_btn {
        border-radius: 12px !important;
        border: 1px solid var(--border);
        color: var(--dark);
        font-weight: 700;
        min-width: 38px;
        text-align: center;
    }

    .pagination .active .page-link,
    .pagination .page-link:hover,
    .pagination_btn:hover {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }

    /* ---------- Live search suggestions ---------- */
    #liveSearchWrapper {
        position: relative;
    }

    .product-suggest-box {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        z-index: 1060;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        box-shadow: 0 18px 45px rgba(17, 24, 39, .18);
        max-height: 400px;
        overflow-y: auto;
        overscroll-behavior: contain;
        padding: 6px;
        animation: suggestSlideDown .18s ease;
    }

    @keyframes suggestSlideDown {
        from {
            opacity: 0;
            transform: translateY(-6px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .ps-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 10px;
        border-radius: 10px;
        text-decoration: none;
        color: var(--dark);
        transition: background .12s ease;
    }

    .ps-item:hover,
    .ps-item.active {
        background: var(--primary-soft);
    }

    .ps-thumb {
        width: 44px;
        height: 44px;
        flex: 0 0 44px;
        border-radius: 8px;
        overflow: hidden;
        background: var(--bg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--muted);
    }

    .ps-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ps-info {
        min-width: 0;
        flex: 1;
    }

    .ps-name {
        font-size: 13px;
        font-weight: 700;
        line-height: 1.35;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .ps-name mark {
        background: rgba(248, 86, 6, .12);
        color: var(--primary);
        font-weight: 800;
        border-radius: 3px;
        padding: 0 1px;
    }

    .ps-meta {
        font-size: 11px;
        color: var(--muted);
        display: flex;
        gap: 10px;
        margin-top: 2px;
    }

    .ps-price {
        font-size: 13px;
        font-weight: 800;
        color: var(--primary);
        white-space: nowrap;
    }

    .ps-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 18px 0;
        color: var(--muted);
        font-size: 13px;
    }

    .ps-empty {
        padding: 16px;
        text-align: center;
        color: var(--muted);
        font-size: 13px;
        font-weight: 600;
    }

    .ps-footer {
        padding: 8px 10px 4px;
        border-top: 1px solid var(--border);
        margin-top: 6px;
        font-size: 11px;
        color: var(--muted);
        text-align: center;
    }

    @media (max-width: 991px) {
        .products__menu {
            position: static;
            margin-bottom: 20px;
        }

        .page_title {
            font-size: 30px;
        }
    }

    @media (max-width: 767px) {
        .breadcrumb__nk {
            padding: 14px 0;
            font-size: 13px;
        }

        .products_title_container {
            padding: 28px 15px 22px;
        }

        .page_title {
            font-size: 26px;
        }

        .products__form {
            flex-direction: column;
            padding: 14px;
            border-radius: 18px;
            margin-bottom: 18px;
        }

        .products__form .form-control,
        .products__form button {
            min-height: 52px;
        }

        .products__menu {
            border-radius: 18px;
            padding: 16px;
            margin-bottom: 18px;
        }

        .filter__text {
            font-size: 18px;
            margin-bottom: 14px;
        }

        .accordion-button {
            padding: 13px 14px;
            font-size: 14px;
        }

        .accordion-body {
            max-height: 220px;
        }

        #productListing {
            border-radius: 18px;
            padding: 12px !important;
        }

        .container.mx-auto.row {
            margin-left: auto !important;
            margin-right: auto !important;
            padding-left: 10px;
            padding-right: 10px;
        }
    }

    @media (max-width: 420px) {
        .page_title {
            font-size: 23px;
        }

        .products__form {
            padding: 12px;
        }

        .products__menu {
            padding: 14px;
        }

        .form-check-label {
            font-size: 13px;
        }
    }
</style>

<div id="products">
    <div class="breadcrumb__nk">
        <div class="container">
            <a href="{{ route('home') }}" class="text-light">{{ trans('language.home') }} </a> /
            {{ trans('language.products') }}
        </div>
    </div>

    <div class="products_title_container container d-flex justify-content-center">
        <div>
            <h1 class="page_title">{{ trans('language.products') }}</h1>
        </div>
    </div>

    <div class="container mx-auto row g-4">
        <div class="col-lg-3 col-sm-12 products__menu">
            <span class="filter__text">{{ trans('language.filter') }}</span>

            <div class="accordion" id="accordionExample">
                <form action="">
                    <div class="accordion-item mb-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseCategory" aria-expanded="false"
                                aria-controls="collapseCategory">
                                {{ trans('language.categories') }}
                            </button>
                        </h2>

                        <div id="collapseCategory" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                @foreach ($categories as $category)
                                    <div class="form-check">
                                        <input class="form-check-input category_for_filter" type="checkbox"
                                            value="{{ $category->id }}" id="brand{{ $category->id }}"
                                            @if (isset($root_category->id) && $root_category->id == $category->id) checked @endif name="category">
                                        <label class="form-check-label" for="brand{{ $category->id }}">
                                            {{ $category->title }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                {{ trans('language.brands') }}
                            </button>
                        </h2>

                        <div id="collapseOne" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                @foreach ($brands as $brand)
                                    <div class="form-check">
                                        <input class="form-check-input brands_for_filter" type="checkbox"
                                            @if (isset($current_brand->id) && $current_brand->id == $brand->id) checked @endif
                                            value="{{ $brand->id }}" id="brand{{ $brand->id }}" name="brand">
                                        <label class="form-check-label" for="brand{{ $brand->id }}">
                                            {{ $brand->title }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    @foreach ($filter_attributes as $attributes)
                        <div class="accordion-item mb-2">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ trim(str_replace(' ', '', $attributes->attribute_name)) }}"
                                    aria-expanded="false"
                                    aria-controls="collapse{{ trim(str_replace(' ', '', $attributes->attribute_name)) }}">
                                    {{ $attributes->attribute_name }}
                                </button>
                            </h2>

                            <div id="collapse{{ trim(str_replace(' ', '', $attributes->attribute_name)) }}"
                                class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    @foreach ($attributes->attributes_values as $value)
                                        <div class="form-check">
                                            <input class="form-check-input attributes_for_filter" type="checkbox"
                                                value="{{ $value->value }}"
                                                id="{{ trim(str_replace(' ', '', $value->value)) }}">
                                            <label class="form-check-label"
                                                for="{{ trim(str_replace(' ', '', $value->value)) }}">
                                                {{ $value->value }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>
        </div>

        <div class="col-lg-9 col-sm-12">
            <form action="" class="products__form d-flex flex-column flex-md-row align-items-center gap-3" id="productSearchForm">
                <div class="form-floating mb-3 mb-md-0 w-100" id="liveSearchWrapper">
                    <input type="text" class="form-control name" id="productNameInput"
                        placeholder="{{ trans('language.label_name') }}" name="name" autocomplete="off"
                        spellcheck="false">
                    <label for="productNameInput">{{ trans('language.label_name') }}</label>

                    <div id="productSuggestBox" class="product-suggest-box d-none"></div>
                </div>

                <div class="form-floating mb-3 mb-md-0 w-100">
                    <button type="submit" id="searchBtn" class="btn w-50 w-md-auto">
                        {{ trans('language.btn_search') }}
                    </button>
                </div>
            </form>

            <div class="p-2" id="productListing"></div>

            <div class="product-pagination pt-4">
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center" id="product_pagination"></ul>
                </nav>
            </div>
        </div>
    </div>
</div>

@push('footer')
<script type="text/javascript">
    let current_category = "{{ optional($current_category)->id }}";

    function getProducts(url, showLoader) {
        showLoader = showLoader !== false;

        if (showLoader) {
            $('body').addClass('loader-open');
        } else {
            // Subtle dim for live-typing updates (smoother than a full-page loader)
            $('#productListing').addClass('listing-loading');
        }

        let form = document.getElementById('productSearchForm');
        var formData = new FormData(form);

        let name = $('#productSearchForm .name').val() || '';
        let model = $('#productSearchForm .model').val() || '';

        formData.set('name', name);
        formData.set('model', model);

        $('.brands_for_filter').each(function() {
            if ($(this).is(':checked')) {
                var brands_for_filter = $(this).val();
                formData.append('brands_for_filter[]', brands_for_filter);
            }
        });

        $('.category_for_filter').each(function() {
            if ($(this).is(':checked')) {
                var category_for_filter = $(this).val();
                formData.append('category_for_filter[]', category_for_filter);
            }
        });

        $('.attributes_for_filter').each(function() {
            if ($(this).is(':checked')) {
                var attributes_for_filter = $(this).val();
                formData.append('attributes_for_filter[]', attributes_for_filter);
            }
        });

        formData.append('current_category', current_category);

        if (url == null) {
            post_url = "{{ route('search.products') }}";
        } else {
            post_url = url;
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: post_url,
            type: "Post",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response) {
                $('body').removeClass('loader-open');
                $('#productListing').removeClass('listing-loading').html(response.products_html);
                $('#product_pagination').html(response.pagination_html);
            },
            error: function() {
                $('body').removeClass('loader-open');
                $('#productListing').removeClass('listing-loading');
            }
        })
    }

    getProducts(null);

    /* ---------- Live search (suggestions + live grid update) ---------- */
    let suggestXhr = null;
    let suggestTimer = null;
    let liveSearchTimer = null;
    let suggestCache = {};
    let suggestItems = [];
    let activeIndex = -1;

    const SUGGEST_URL = "{{ route('product.suggest') }}";
    const noResultText = "{{ trans('language.no_product_found') }}";

    function escapeHtml(str) {
        return String(str ?? '').replace(/[&<>"']/g, s =>
            ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[s]));
    }

    function highlightMatch(text, q) {
        if (!text) return '';
        const idx = text.toLowerCase().indexOf(q.toLowerCase());
        if (idx === -1) return escapeHtml(text);
        return escapeHtml(text.slice(0, idx)) +
            '<mark>' + escapeHtml(text.slice(idx, idx + q.length)) + '</mark>' +
            escapeHtml(text.slice(idx + q.length));
    }

    function showSuggestLoading() {
        $('#productSuggestBox')
            .removeClass('d-none')
            .html('<div class="ps-loading"><span class="spinner-border spinner-border-sm"></span></div>');
    }

    function renderSuggestions(items, q) {
        const $box = $('#productSuggestBox');
        suggestItems = items || [];
        activeIndex = -1;

        if (!suggestItems.length) {
            $box.removeClass('d-none')
                .html('<div class="ps-empty">' + escapeHtml(noResultText) + '</div>');
            return;
        }

        let html = '';
        suggestItems.forEach(function (item) {
            const img = item.thumbnail
                ? '<img src="' + escapeHtml(item.thumbnail) + '" alt="" loading="lazy">'
                : '<i class="fa-solid fa-box-open"></i>';

            html += '<a class="ps-item" href="' + escapeHtml(item.url) + '">' +
                '<div class="ps-thumb">' + img + '</div>' +
                '<div class="ps-info">' +
                    '<div class="ps-name">' + highlightMatch(item.name, q) + '</div>' +
                    '<div class="ps-meta">' +
                        (item.code ? '<span>Code: ' + escapeHtml(item.code) + '</span>' : '') +
                        (item.brand ? '<span>' + escapeHtml(item.brand) + '</span>' : '') +
                    '</div>' +
                '</div>' +
                (item.price ? '<div class="ps-price">৳' + escapeHtml(item.price) + '</div>' : '') +
            '</a>';
        });

        html += '<div class="ps-footer">↑ ↓ to navigate &nbsp;•&nbsp; Enter to open</div>';
        $box.removeClass('d-none').html(html);
    }

    function fetchSuggestions(q) {
        if (suggestXhr && suggestXhr.readyState !== 4) {
            suggestXhr.abort();
        }
        showSuggestLoading();

        suggestXhr = $.get(SUGGEST_URL, { q: q }, null, 'json')
            .done(function (response) {
                suggestCache[q] = response.suggestions;
                renderSuggestions(response.suggestions, q);
            })
            .fail(function (xhr) {
                if (xhr.statusText !== 'abort' && xhr.status !== 0) {
                    $('#productSuggestBox').addClass('d-none');
                }
            });
    }

    $('#productNameInput').on('input', function () {
        const q = $(this).val().trim();
        clearTimeout(suggestTimer);
        clearTimeout(liveSearchTimer);

        if (q.length < 1) {
            $('#productSuggestBox').addClass('d-none').empty();
            suggestItems = [];
            current_category = '';
            liveSearchTimer = setTimeout(function () { getProducts(null, false); }, 300);
            return;
        }

        // Suggestions: instant for cached queries, debounced for new ones
        suggestTimer = setTimeout(function () {
            if (suggestCache[q]) {
                renderSuggestions(suggestCache[q], q);
            } else {
                fetchSuggestions(q);
            }
        }, suggestCache[q] ? 0 : 180);

        // Live grid update (debounced, feels instant but avoids request spam)
        current_category = '';
        liveSearchTimer = setTimeout(function () { getProducts(null, false); }, 450);
    });

    $('#productNameInput').on('focus', function () {
        const q = $(this).val().trim();
        if (q.length >= 1) {
            if (suggestCache[q]) renderSuggestions(suggestCache[q], q);
            else fetchSuggestions(q);
        }
    });

    $('#productNameInput').on('keydown', function (e) {
        const $box = $('#productSuggestBox');
        if ($box.hasClass('d-none') || !suggestItems.length) return;

        if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
            e.preventDefault();
            activeIndex = e.key === 'ArrowDown'
                ? (activeIndex + 1) % suggestItems.length
                : (activeIndex - 1 + suggestItems.length) % suggestItems.length;

            const $items = $box.find('.ps-item').removeClass('active');
            $items.eq(activeIndex).addClass('active');
            $box.scrollTop($items.eq(activeIndex).position().top + $box.scrollTop() - 80);
        } else if (e.key === 'Enter' && activeIndex >= 0) {
            e.preventDefault();
            window.location.href = suggestItems[activeIndex].url;
        } else if (e.key === 'Escape') {
            $box.addClass('d-none');
        }
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#liveSearchWrapper').length) {
            $('#productSuggestBox').addClass('d-none');
        }
    });

    $('#productSearchForm').on('submit', function (e) {
        e.preventDefault();
        $('#productSuggestBox').addClass('d-none');
        current_category = '';
        getProducts(null);
    });

    $(document).on('click', '#searchBtn', function(e) {
        e.preventDefault();
        current_category = '';
        getProducts(null);
    })

    $(document).on('change', '.brands_for_filter', function(e) {
        e.preventDefault();
        current_category = '';
        getProducts(null);
    })

    $(document).on('change', '.category_for_filter', function(e) {
        e.preventDefault();
        current_category = '';
        getProducts(null);
    })

    $(document).on('change', '.attributes_for_filter', function(e) {
        e.preventDefault();
        current_category = '';
        getProducts(null);
    })

    $(document).on('click', '.pagination_btn', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        getProducts(url);
        $('html, body').scrollTop($("#products").offset().top - 100);
    })

    $(document).ready(function() {
        function checkScreenSize() {
            if ($(window).width() < 768) {
                $(".accordion-collapse").removeClass("show");
                $(".accordion-button").addClass("collapsed");
                $(".accordion-button").attr("aria-expanded", "false");
            }
        }

        checkScreenSize();

        $(window).resize(function() {
            checkScreenSize();
        });
    })
</script>
@endpush
@endsection
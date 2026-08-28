<style>
    /* ============================================================
       Product card — injected per AJAX response
    ============================================================ */
    :root {
        --pc-radius: 16px;
        --pc-shadow: 0 2px 10px rgba(0,0,0,.07);
        --pc-shadow-h: 0 10px 30px rgba(0,0,0,.13);
        --pc-pr: #f85606;
        --pc-pr-grd: linear-gradient(135deg, #f85606, #ff8a00);
        --pc-inq-grd: linear-gradient(135deg, #6366f1, #8b5cf6);
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
    }

    /* Smooth appearance for initial + lazily appended cards */
    .products-grid > div {
        animation: pcFadeIn .35s ease both;
    }

    @keyframes pcFadeIn {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @media (prefers-reduced-motion: reduce) {
        .products-grid > div { animation: none; }
    }

    /* === Card === */
    .pc-wrap {
        text-decoration: none;
        color: inherit;
        display: block;
        cursor: pointer;
    }

    .pc {
        background: #fff;
        border-radius: var(--pc-radius);
        box-shadow: var(--pc-shadow);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: transform .22s ease, box-shadow .22s ease;
        position: relative;
    }

    .pc:hover {
        transform: translateY(-4px);
        box-shadow: var(--pc-shadow-h);
    }

    /* === Image === */
    .pc-img-wrap {
        position: relative;
        overflow: hidden;
        background: #f8fafc;
        aspect-ratio: 4 / 3;
    }

    .pc-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .35s ease;
        display: block;
    }

    .pc:hover .pc-img { transform: scale(1.06); }

    /* Discount badge */
    .pc-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: linear-gradient(135deg, #ef4444, #f97316);
        color: #fff;
        font-size: 10px;
        font-weight: 800;
        border-radius: 8px;
        padding: 3px 8px;
        letter-spacing: .3px;
        z-index: 2;
        line-height: 1.4;
    }

    /* Quick action (wishlist placeholder) */
    .pc-quick {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 30px;
        height: 30px;
        background: rgba(255,255,255,.88);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
        opacity: 0;
        transform: translateY(-4px);
        transition: opacity .2s ease, transform .2s ease;
        z-index: 2;
        font-size: 12px;
        color: #6b7280;
        border: none;
        cursor: pointer;
    }

    .pc:hover .pc-quick { opacity: 1; transform: translateY(0); }

    /* === Body === */
    .pc-body {
        padding: 12px 12px 14px;
        display: flex;
        flex-direction: column;
        flex: 1;
        gap: 4px;
    }

    .pc-name {
        font-size: 13px;
        font-weight: 700;
        line-height: 1.4;
        color: #0f172a;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-transform: uppercase;
        letter-spacing: .2px;
        min-height: 36px;
    }

    .pc-name-link {
        text-decoration: none;
        color: #0f172a;
        transition: color .18s ease;
    }

    .pc-name-link:hover { color: #f85606; }
    .pc-name-row {
        display: flex;
        align-items: flex-start;
        gap: 6px;
        min-height: 36px;
    }

    .pc-name-row .pc-name {
        flex: 1;
        min-height: 0;
    }

    .pc-cat-badge {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        background: #fff3ec;
        color: #f85606;
        border: 1px solid rgba(248,86,6,.22);
        border-radius: 6px;
        padding: 2px 7px;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .4px;
        text-transform: uppercase;
        white-space: nowrap;
        margin-top: 2px;
        line-height: 1.4;
    }

    /* Brand row */
    .pc-brand-row {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        font-weight: 700;
        color: #6b7280;
        margin-top: 1px;
    }

    .pc-brand-row i {
        font-size: 9px;
        color: #f85606;
        flex-shrink: 0;
    }

    .pc-code {
        font-size: 11px;
        color: #9ca3af;
        font-weight: 500;
        letter-spacing: .2px;
    }

    /* === Price === */
    .pc-price-row {
        display: flex;
        align-items: baseline;
        gap: 6px;
        margin-top: 2px;
        flex-wrap: wrap;
    }

    .pc-price-main {
        font-size: 15px;
        font-weight: 800;
        color: var(--pc-pr);
        line-height: 1;
    }

    .pc-price-original {
        font-size: 11px;
        color: #9ca3af;
        text-decoration: line-through;
        font-weight: 500;
    }

    /* === Attributes === */
    .pc-attrs {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-top: 4px;
    }

    .pc-attr-chip {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 600;
        color: #475569;
        padding: 2px 7px;
        white-space: nowrap;
    }

    /* === Action button === */
    .pc-btn {
        width: 100%;
        border: none;
        border-radius: 10px;
        padding: 9px 12px;
        font-size: 12px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
        transition: opacity .2s ease, transform .2s ease, box-shadow .2s ease;
        margin-top: auto;
        padding-top: 10px;
        text-decoration: none;
        line-height: 1;
    }

    .pc-btn-cart {
        background: var(--pc-pr-grd);
        color: #fff;
        box-shadow: 0 4px 12px rgba(248,86,6,.22);
    }

    .pc-btn-cart:hover {
        opacity: .92;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(248,86,6,.32);
        color: #fff;
    }

    .pc-btn-inq {
        background: var(--pc-inq-grd);
        color: #fff;
        box-shadow: 0 4px 12px rgba(99,102,241,.22);
    }

    .pc-btn-inq:hover {
        opacity: .92;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(99,102,241,.32);
        color: #fff;
    }

    /* === Empty state === */
    .pc-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        color: #6b7280;
    }

    .pc-empty i {
        font-size: 52px;
        color: #d1d5db;
        display: block;
        margin-bottom: 16px;
    }

    .pc-empty h4 {
        font-size: 18px;
        font-weight: 700;
        color: #374151;
        margin-bottom: 6px;
    }

    .pc-empty p {
        font-size: 14px;
        color: #9ca3af;
        margin: 0;
    }

    /* ============================================================
       Responsive
    ============================================================ */
    @media (max-width: 1200px) {
        .products-grid { grid-template-columns: repeat(3, 1fr); }
    }

    @media (max-width: 767px) {
        .products-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }

        .pc-name { font-size: 12px; min-height: 32px; }

        .pc-price-main { font-size: 14px; }

        .pc-btn { font-size: 11px; padding: 8px 10px; }

        .pc-body { padding: 10px 10px 12px; }
    }

    @media (max-width: 359px) {
        .products-grid { grid-template-columns: repeat(2, 1fr); gap: 8px; }

        .pc-name { font-size: 11px; }
    }
</style>

<div class="products-grid" role="list">

    @if ($products->count() > 0)

        @foreach ($products as $product)

            @php
                $originalPrice  = $product->price;
                $discount       = $product->discount ?? 0;
                $discountType   = $product->discount_type ?? 'percent';
                $discountedPrice = $originalPrice;

                if ($discount > 0) {
                    $discountedPrice = ($discountType === 'amount')
                        ? $originalPrice - $discount
                        : $originalPrice - ($originalPrice * $discount / 100);
                }

                $feature_product_attributes = Cache::remember(
                    "feature_product_attributes_{$product->id}",
                    now()->addHours(1),
                    fn () => $product->attributes
                );

                $productName = Str::limit(
                    $product->getTranslation(Session::get('language') ?? 'en', 'title') ?? $product->name,
                    50, '…'
                );
            @endphp

            <div role="listitem">
                <div class="pc-wrap" data-href="{{ url('product/' . $product->slug) }}" aria-label="{{ $productName }}">
                    <article class="pc">

                        {{-- Image --}}
                        <div class="pc-img-wrap">
                            <img
                                src="{{ asset('uploads/product-images/' . $product->thumbnail) }}"
                                class="pc-img"
                                alt="{{ $productName }}"
                                loading="lazy">

                            @if ($discount > 0)
                                <span class="pc-badge">
                                    @if ($discountType === 'amount')
                                        -৳{{ number_format($discount, 0) }}
                                    @else
                                        {{ $discount }}% OFF
                                    @endif
                                </span>
                            @endif

                            <button type="button" class="pc-quick" aria-label="View product" tabindex="-1">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>

                        {{-- Body --}}
                        <div class="pc-body">

                            {{-- Name + Category badge on same line --}}
                            <div class="pc-name-row">
                                <a href="{{ url('product/' . $product->slug) }}" class="pc-name pc-name-link">{{ $productName }}</a>
                                @if ($product->category)
                                    <span class="pc-cat-badge">{{ $product->category->title }}</span>
                                @endif
                            </div>

                            {{-- Brand --}}
                            @if ($product->brand)
                                <div class="pc-brand-row">
                                    <i class="fa-solid fa-tag"></i>
                                    {{ $product->brand->title }}
                                </div>
                            @endif

                            @if ($product->code)
                                <span class="pc-code">{{ $product->code }}</span>
                            @endif

                            {{-- Price --}}
                            @if ($originalPrice > 0)
                                <div class="pc-price-row">
                                    <span class="pc-price-main">
                                        ৳{{ number_format($discountedPrice, 0) }}
                                    </span>
                                    @if ($discount > 0)
                                        <span class="pc-price-original">৳{{ number_format($originalPrice, 0) }}</span>
                                    @endif
                                </div>
                            @endif

                            {{-- Attribute chips --}}
                            @php $filterAttrs = $feature_product_attributes->where('is_filter', 1)->take(3); @endphp
                            @if ($filterAttrs->count() > 0)
                                <div class="pc-attrs">
                                    @foreach ($filterAttrs as $attribute)
                                        <span class="pc-attr-chip">{{ $attribute->attribute_name }}: {{ $attribute->value }}</span>
                                    @endforeach
                                </div>
                            @endif

                            {{-- CTA --}}
                            <div style="padding-top:10px; margin-top:auto;">
                                @if ($originalPrice > 0)
                                    <a href="{{ route('add.to.cart', ['type' => 'product', 'id' => $product->id]) }}"
                                        class="pc-btn pc-btn-cart add-to-cart">
                                        <i class="fa-solid fa-cart-shopping"></i>
                                        <span>{{ trans('language.btn_add_to_cart') ?? 'Add to Cart' }}</span>
                                    </a>
                                @else
                                    <a href="{{ route('add.to.inquiry', $product->id) }}"
                                        class="pc-btn pc-btn-inq inquiry-btn-small">
                                        <i class="fa-solid fa-circle-question"></i>
                                        <span>{{ trans('language.btn_add_to_inquiry_list') ?? 'Inquiry' }}</span>
                                    </a>
                                @endif
                            </div>

                        </div>
                    </article>
                </div>
            </div>

        @endforeach

    @else

        <div class="pc-empty">
            <i class="fa-solid fa-box-open"></i>
            <h4>{{ trans('language.no_product_found') }}</h4>
            <p>Try adjusting your filters or search term.</p>
        </div>

    @endif

</div>

<script>
(function () {
    // Make the whole card clickable via data-href, but skip clicks on
    // interactive elements (links, buttons) so they handle themselves.
    document.querySelectorAll('.pc-wrap[data-href]').forEach(function (card) {
        card.addEventListener('click', function (e) {
            // If the click target (or any ancestor up to the card) is an
            // <a> or <button>, let it do its own thing.
            var el = e.target;
            while (el && el !== card) {
                if (el.tagName === 'A' || el.tagName === 'BUTTON') return;
                el = el.parentElement;
            }
            window.location.href = card.dataset.href;
        });
    });
})();
</script>

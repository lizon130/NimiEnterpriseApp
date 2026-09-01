<style>
    :root {
        --pc-radius: 16px;
        --pc-shadow: 0 2px 10px rgba(0, 0, 0, .07);
        --pc-shadow-h: 0 10px 30px rgba(0, 0, 0, .13);
        --pc-pr: #f85606;
        --pc-pr-grd: linear-gradient(135deg, #f85606, #ff8a00);
        --pc-inq-grd: linear-gradient(135deg, #6366f1, #8b5cf6);
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .products-grid > [role="listitem"] {
        min-width: 0;
        animation: pcFadeIn .3s ease both;
    }

    @keyframes pcFadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .products-grid > [role="listitem"] {
            animation: none;
        }
    }

    .pc-wrap {
        display: block;
        height: 100%;
        text-decoration: none;
        color: inherit;
        cursor: pointer;
    }

    .pc {
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
        background: #fff;
        border: 1px solid #eef0f3;
        border-radius: var(--pc-radius);
        box-shadow: var(--pc-shadow);
        transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
    }

    .pc:hover {
        transform: translateY(-4px);
        border-color: rgba(248, 86, 6, .22);
        box-shadow: var(--pc-shadow-h);
    }

    .pc-img-wrap {
        position: relative;
        overflow: hidden;
        aspect-ratio: 4 / 3;
        background: #f8fafc;
    }

    .pc-img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .35s ease;
    }

    .pc:hover .pc-img {
        transform: scale(1.045);
    }

    .pc-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 2;
        padding: 4px 9px;
        border-radius: 8px;
        background: linear-gradient(135deg, #ef4444, #f97316);
        box-shadow: 0 5px 14px rgba(239, 68, 68, .23);
        color: #fff;
        font-size: 10px;
        font-weight: 800;
        line-height: 1.35;
        letter-spacing: .2px;
    }

    .pc-quick {
        position: absolute;
        top: 9px;
        right: 9px;
        z-index: 2;
        width: 32px;
        height: 32px;
        display: grid;
        place-items: center;
        border: 1px solid rgba(255, 255, 255, .75);
        border-radius: 50%;
        background: rgba(255, 255, 255, .9);
        color: #64748b;
        box-shadow: 0 4px 12px rgba(15, 23, 42, .1);
        backdrop-filter: blur(5px);
        opacity: 0;
        transform: translateY(-4px);
        transition: .2s ease;
    }

    .pc:hover .pc-quick {
        opacity: 1;
        transform: translateY(0);
    }

    .pc-body {
        display: flex;
        flex: 1;
        flex-direction: column;
        gap: 5px;
        padding: 12px 12px 14px;
    }

    .pc-name-row {
        display: flex;
        align-items: flex-start;
        gap: 6px;
        min-height: 38px;
    }

    .pc-name {
        min-width: 0;
        flex: 1;
        display: -webkit-box;
        overflow: hidden;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        color: #0f172a;
        font-size: 13px;
        font-weight: 750;
        line-height: 1.4;
        letter-spacing: .1px;
        text-transform: uppercase;
    }

    .pc-name-link {
        text-decoration: none;
        color: #0f172a;
    }

    .pc-name-link:hover {
        color: var(--pc-pr);
    }

    .pc-cat-badge {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        max-width: 90px;
        overflow: hidden;
        padding: 2px 7px;
        border: 1px solid rgba(248, 86, 6, .2);
        border-radius: 6px;
        background: #fff3ec;
        color: #f85606;
        font-size: 9px;
        font-weight: 800;
        line-height: 1.4;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .pc-brand-row {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
    }

    .pc-brand-row i {
        color: var(--pc-pr);
        font-size: 9px;
    }

    .pc-code {
        color: #94a3b8;
        font-size: 11px;
        font-weight: 500;
    }

    .pc-price-row {
        display: flex;
        flex-wrap: wrap;
        align-items: baseline;
        gap: 6px;
        margin-top: 2px;
    }

    .pc-price-main {
        color: var(--pc-pr);
        font-size: 15px;
        font-weight: 850;
        line-height: 1.15;
    }

    .pc-price-original {
        color: #9ca3af;
        font-size: 11px;
        font-weight: 500;
        text-decoration: line-through;
    }

    .pc-discount-note {
        color: #16a34a;
        font-size: 10px;
        font-weight: 750;
    }

    .pc-attrs {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-top: 4px;
    }

    .pc-attr-chip {
        max-width: 100%;
        overflow: hidden;
        padding: 2px 7px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        background: #f8fafc;
        color: #475569;
        font-size: 10px;
        font-weight: 650;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .pc-action-wrap {
        margin-top: auto;
        padding-top: 10px;
    }

    .pc-btn {
        width: 100%;
        min-height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 9px 12px;
        border: none;
        border-radius: 10px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 800;
        line-height: 1;
        transition: transform .2s ease, opacity .2s ease, box-shadow .2s ease;
    }

    .pc-btn-cart {
        background: var(--pc-pr-grd);
        color: #fff;
        box-shadow: 0 4px 12px rgba(248, 86, 6, .22);
    }

    .pc-btn-cart:hover {
        transform: translateY(-1px);
        color: #fff;
        box-shadow: 0 7px 18px rgba(248, 86, 6, .3);
    }

    .pc-btn-inq {
        background: var(--pc-inq-grd);
        color: #fff;
        box-shadow: 0 4px 12px rgba(99, 102, 241, .2);
    }

    .pc-btn-inq:hover {
        transform: translateY(-1px);
        color: #fff;
    }

    .pc-empty {
        grid-column: 1 / -1;
        padding: 62px 20px;
        text-align: center;
        color: #64748b;
    }

    .pc-empty i {
        display: block;
        margin-bottom: 14px;
        color: #cbd5e1;
        font-size: 50px;
    }

    .pc-empty h4 {
        margin-bottom: 6px;
        color: #334155;
        font-size: 18px;
        font-weight: 750;
    }

    .pc-empty p {
        margin: 0;
        color: #94a3b8;
        font-size: 13px;
    }

    #productListing.list-view-active .products-grid {
        grid-template-columns: 1fr;
    }

    #productListing.list-view-active .pc {
        min-height: 155px;
        flex-direction: row;
    }

    #productListing.list-view-active .pc-img-wrap {
        width: 190px;
        flex: 0 0 190px;
        aspect-ratio: auto;
    }

    #productListing.list-view-active .pc-body {
        padding: 16px;
    }

    @media (max-width: 1200px) {
        .products-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 767px) {
        .products-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .pc-body {
            padding: 10px 10px 12px;
        }

        .pc-name-row {
            min-height: 34px;
        }

        .pc-name {
            font-size: 12px;
        }

        .pc-cat-badge {
            max-width: 70px;
            padding: 2px 5px;
            font-size: 8px;
        }

        .pc-price-main {
            font-size: 14px;
        }

        .pc-btn {
            min-height: 36px;
            padding: 8px 9px;
            font-size: 11px;
        }

        .pc-quick {
            opacity: 1;
            transform: none;
        }

        #productListing.list-view-active .pc-img-wrap {
            width: 125px;
            flex-basis: 125px;
        }
    }

    @media (max-width: 359px) {
        .products-grid {
            gap: 8px;
        }

        .pc-name {
            font-size: 11px;
        }
    }
</style>

<div class="products-grid" role="list">
    @forelse ($products as $product)
        @php
            $originalPrice = (float) ($product->price ?? 0);
            $discount = (float) ($product->discount ?? 0);
            $discountType = strtolower(trim((string) ($product->discount_type ?? 'percent')));
            $discountedPrice = $originalPrice;
            $effectiveDiscountPercent = 0;

            if ($discount > 0 && $originalPrice > 0) {
                if (in_array($discountType, ['amount', 'fixed', 'flat'], true)) {
                    $discountedPrice = max(0, $originalPrice - $discount);
                    $effectiveDiscountPercent = min(100, ($discount / $originalPrice) * 100);
                } else {
                    $discountedPrice = max(0, $originalPrice - (($originalPrice * $discount) / 100));
                    $effectiveDiscountPercent = min(100, $discount);
                }
            }

            $featureProductAttributes = $product->relationLoaded('attributes')
                ? $product->attributes
                : $product->attributes()->get();

            $filterAttrs = $featureProductAttributes->where('is_filter', 1)->take(3);

            $productName = Str::limit(
                $product->getTranslation(Session::get('language') ?? 'en', 'title') ?? $product->name,
                50,
                '…'
            );
        @endphp

        <div role="listitem" data-product-id="{{ $product->id }}">
            <div
                class="pc-wrap"
                data-href="{{ url('product/' . $product->slug) }}"
                aria-label="{{ $productName }}">

                <article class="pc">
                    <div class="pc-img-wrap">
                        <img
                            src="{{ asset('uploads/product-images/' . $product->thumbnail) }}"
                            class="pc-img"
                            alt="{{ $productName }}"
                            loading="lazy"
                            decoding="async">

                        @if ($discount > 0)
                            <span class="pc-badge">
                                @if (in_array($discountType, ['amount', 'fixed', 'flat'], true))
                                    -৳{{ number_format($discount, 0) }}
                                @else
                                    {{ number_format($discount, 0) }}% OFF
                                @endif
                            </span>
                        @endif

                        <button
                            type="button"
                            class="pc-quick"
                            aria-label="View product"
                            tabindex="-1">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>

                    <div class="pc-body">
                        <div class="pc-name-row">
                            <a
                                href="{{ url('product/' . $product->slug) }}"
                                class="pc-name pc-name-link">
                                {{ $productName }}
                            </a>

                            @if ($product->category)
                                <span class="pc-cat-badge" title="{{ $product->category->title }}">
                                    {{ $product->category->title }}
                                </span>
                            @endif
                        </div>

                        @if ($product->brand)
                            <div class="pc-brand-row">
                                <i class="fa-solid fa-tag"></i>
                                <span>{{ $product->brand->title }}</span>
                            </div>
                        @endif

                        @if ($product->code)
                            <span class="pc-code">{{ $product->code }}</span>
                        @endif

                        @if ($originalPrice > 0)
                            <div class="pc-price-row">
                                <span class="pc-price-main">
                                    ৳{{ number_format($discountedPrice, 0) }}
                                </span>

                                @if ($discount > 0)
                                    <span class="pc-price-original">
                                        ৳{{ number_format($originalPrice, 0) }}
                                    </span>

                                    <span class="pc-discount-note">
                                        Save {{ number_format($effectiveDiscountPercent, 0) }}%
                                    </span>
                                @endif
                            </div>
                        @endif

                        @if ($filterAttrs->count() > 0)
                            <div class="pc-attrs">
                                @foreach ($filterAttrs as $attribute)
                                    <span class="pc-attr-chip">
                                        {{ $attribute->attribute_name }}: {{ $attribute->value }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="pc-action-wrap">
                            @if ($originalPrice > 0)
                                <a
                                    href="{{ route('add.to.cart', ['type' => 'product', 'id' => $product->id]) }}"
                                    class="pc-btn pc-btn-cart add-to-cart"
                                    data-product-id="{{ $product->id }}">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    <span>{{ trans('language.btn_add_to_cart') ?? 'Add to Cart' }}</span>
                                </a>
                            @else
                                <a
                                    href="{{ route('add.to.inquiry', $product->id) }}"
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
    @empty
        <div class="pc-empty">
            <i class="fa-solid fa-box-open"></i>
            <h4>{{ trans('language.no_product_found') }}</h4>
            <p>Try adjusting your filters or search term.</p>
        </div>
    @endforelse
</div>

{{--
    IMPORTANT:
    Do not put per-card click JavaScript here.
    This partial is loaded through AJAX and infinite scroll.
    The main products.blade.php uses delegated events, so all future cards work automatically.
--}}

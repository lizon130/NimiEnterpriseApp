<div class="row g-2">

    @if ($products->count() > 0)

        @foreach ($products as $product)

            @php
                $originalPrice = $product->price;
                $discount = $product->discount ?? 0;

                $discountedPrice = $originalPrice;

                if ($discount > 0) {
                    $discountedPrice = $originalPrice - ($originalPrice * $discount) / 100;
                }

                $feature_product_attributes = Cache::remember(
                    "feature_product_attributes_{$product->id}",
                    now()->addHours(1),
                    function () use ($product) {
                        return $product->attributes;
                    },
                );
            @endphp

            <div class="col-6 col-md-4 col-lg-3">

                <a href="{{ url('product/' . $product->slug) }}"
                    class="text-decoration-none text-dark">

                    <div class="card border-0 shadow-sm product-card h-100">

                        {{-- Product Image --}}
                        <div class="position-relative overflow-hidden">

                            <img src="{{ asset('uploads/product-images/' . $product->thumbnail) }}"
                                class="card-img-top product-image"
                                alt="{{ $product->name }}">

                            @if ($discount > 0)
                                <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                                    {{ $discount }}% OFF
                                </span>
                            @endif

                        </div>

                        {{-- Product Content --}}
                        <div class="card-body p-2 d-flex flex-column">

                            {{-- Product Name --}}
                            <h6 class="product-title text-uppercase mb-1">
                                {{ Str::limit($product->getTranslation(Session::get('language') ?? 'en', 'title') ?? $product->name, 40, '...') }}
                            </h6>

                            {{-- Product Code --}}
                            <small class="text-muted mb-2">
                                Item Code: {{ $product->code }}
                            </small>

                            {{-- Price --}}
                            <div class="mb-2">

                                @if ($discount > 0)

                                    <span class="fw-bold text-danger fs-6">
                                        ৳{{ number_format($discountedPrice, 2) }}
                                    </span>

                                    <small class="text-muted ms-1">
                                        <del>৳{{ number_format($originalPrice, 2) }}</del>
                                    </small>

                                @else

                                    <span class="fw-bold fs-6">
                                        ৳{{ number_format($originalPrice, 2) }}
                                    </span>

                                @endif

                            </div>

                            {{-- Attributes --}}
                            @if (count($feature_product_attributes) > 0)

                                <div class="mt-auto">

                                    @foreach ($feature_product_attributes as $attribute)

                                        @if ($attribute->is_filter == 1)

                                            <small class="badge bg-light text-dark border me-1 mb-1">
                                                {{ $attribute->attribute_name }}:
                                                {{ $attribute->value }}
                                            </small>

                                        @endif

                                    @endforeach

                                </div>

                            @endif

                        </div>

                    </div>

                </a>

            </div>

        @endforeach

    @else

        <div class="text-center py-5">
            <h4>{{ trans('language.no_product_found') }}</h4>
        </div>

    @endif

</div>

<style>
    .product-card {
        border-radius: 12px;
        transition: 0.3s ease;
        overflow: hidden;
        background: #fff;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .product-image {
        height: 220px;
        width: 100%;
        object-fit: cover;
        transition: 0.3s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .product-title {
        font-size: 14px;
        font-weight: 600;
        line-height: 1.4;
        min-height: 40px;
    }

    @media(max-width: 768px) {
        .product-image {
            height: 170px;
        }

        .product-title {
            font-size: 13px;
        }
    }
</style>

<div class="row">
    @if ($products->count() > 0)
        @foreach ($products as $product)
            <a class="col-6 col-md-4 col-lg-2 ps-1 pe-1 mb-2" href="{{ url('product/'.$product->slug) }}">
                <div class="card product-cart">
                    <img src="{{ asset('uploads/product-images/' . $product->thumbnail) }}" alt="">
                    <p title="{{$product->name}}" class="product-title text-uppercase d-flex flex-column">
						<span>{{ Str::limit($product->getTranslation(Session::get('language') ?? 'en', 'title') ?? $product->name, 30, '...')  }}</span>
						<small>Item Code: {{ $product->code }}</small>
						@php
							$feature_product_attributes = $product->attributes;
						@endphp
						@if(count($feature_product_attributes) > 0)
							<small class="text-capitalize">
								@foreach($feature_product_attributes as $attribute)
									@if($attribute->is_filter == 1)
										{{ $attribute->attribute_name}}: {{ $attribute->value }},
									@endif
								@endforeach
							</small>
						@endif
					</p>
                </div>
            </a>
        @endforeach
    @else
        <h1 style="width: max-content;">{{ trans('language.no_product_found') }}.</h1>
    @endif
</div>
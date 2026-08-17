@extends('frontend.layout.app')
@section('content')
    <div id="products">
        <div class="breadcrumb__nk">
            <div class="container"><a href="{{ route('home') }}" class="text-light">{{ trans('language.home') }}</a> / <a href="{{ route('products') }}" class="text-light">{{ trans('language.products') }}</a> / Search result for “{{ $search_text ?? '' }}” found <b>{{ count($products) }}</b> products @if(count($parts) > 0) and {{ count($parts) }} parts & acessories @endif</div>
        </div>
        
        <div class="container">
            <div class="row mt-2">
                @if ($products->count() > 0)
					<h2 class="page_title m-2">Products</h2>
                    @foreach ($products as $product)
                        <div class="col-lg-2 mb-2">
                            <a href="{{ url('product/'.$product->slug) }}">
                                <div class="card product-cart">
                                    <img src="{{ asset('uploads/product-images/' . $product->thumbnail) }}" height="180px" alt="">
                                    <p title="{{$product->name}}" class="product-title text-uppercase d-flex flex-column">
										<span>{{ Str::limit($product->getTranslation(Session::get('language') ?? 'en', 'title') ?? $product->name, 30, '...')  }}</span>
										<small>Item Code: {{ $product->code }}</small>
										@php
											$feature_product_attributes = Cache::remember("feature_product_attributes_{$product->id}", now()->addHours(1), function () use ($product) {
												return $product->attributes;
											});
										@endphp
										@if(count($feature_product_attributes) > 0)
											<small >
												@foreach($feature_product_attributes as $attribute)
													@if($attribute->is_filter == 1)
														<span class="text-capitalize" >{{ $attribute->attribute_name}}</span>: <span class="text-lowercase">{{ $attribute->value }}</span>,
													@endif
												@endforeach
											</small>
										@endif
									</p>
								</div>
                            </a>
                        </div>
                    @endforeach
                @else
                    <p>{{ trans('language.no_product_found') }}.</p>
                @endif
            </div>
			<div class="row mt-5">
                @if ($parts->count() > 0)
					<h2 class="page_title m-2">Parts / Accessories </h2>
                    @foreach ($parts as $part)
                        <div class="col-lg-2 mb-2">
                            <a href="{{ route('parts.details', $part->slug) }}">
                                <div class="card product-cart">
                                    <img src="{{ asset('uploads/part-images/' . $part->thumbnail) }}" height="180px" alt="">
                                    <p title="{{$part->name}}" class="product-title text-uppercase d-flex flex-column">
										<span>{{ Str::limit($part->getTranslation(Session::get('language') ?? 'en', 'title') ?? $part->name, 30, '...')  }}</span>
										<small>Item Code: {{ $part->code }}</small>
										@php
											$feature_product_attributes = Cache::remember("feature_product_attributes_{$part->id}", now()->addHours(1), function () use ($part) {
												return $part->attributes;
											});
										@endphp
										@if(count($feature_product_attributes) > 0)
											<small>
												@foreach($feature_product_attributes as $attribute)
													@if($attribute->is_filter == 1)
														<span class="text-capitalize" >{{ $attribute->attribute_name}}</span>: <span class="text-lowercase">{{ $attribute->value }}</span>,
													@endif
												@endforeach
											</small>
										@endif
									</p>
								</div>
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    @push('footer')
        <script type="text/javascript">
            
        </script>
    @endpush
@endsection

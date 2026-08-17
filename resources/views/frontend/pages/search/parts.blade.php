<div class="row">
	@if ($parts->count() > 0)    
		@foreach ($parts as $part)
			<a class="col-6 col-md-4 col-lg-3 ps-1 pe-1 mb-2" href="{{ route('parts.details', $part->slug) }}">
				<div class="card product-cart">
					<img src="{{ asset('uploads/part-images/' . $part->thumbnail) }}" height="250px" alt="">
					<p title="{{$part->name}}" class="product-title text-uppercase d-flex flex-column">
						<span>{{ Str::limit($part->getTranslation(Session::get('language') ?? 'en', 'title') ?? $part->name, 30, '...')  }}</span>
						<small>Item Code: {{ $part->code }}</small>
						@php
							$feature_product_attributes = Cache::remember("feature_product_attributes_{$part->id}", now()->addHours(1), function () use ($part) {
								return $part->attributes;
							});
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
		<h1 style="width: max-content;">{{ trans('language.no_part_found') }}.</h1>
	@endif
</div>
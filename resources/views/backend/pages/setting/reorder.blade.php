@extends('backend.layout.app')
@section('title', 'Settings | '.Helper::getSettings('application_name') ?? 'Machine Tool Solution' )
@push('head')
	<link href="{{ asset('assets/css/backend/nested.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Settings</h4>

        <div class="card my-2">
            <div class="card-header">
                <div class="row ">
                    <div class="col-12 d-flex justify-content-between">
                        <div class="d-flex align-items-center"><h5 class="m-0">Re-Order category & products</h5></div>
                    </div>
                </div>
            </div>
            <div class="card-body pb-0">
				<p class="content_title">All Categories</p>
				<form action="{{ route('admin.setting.reorder.post')}}" method="post">
					@csrf
					<div class="cf nestable-lists">
						<div class="dd" id="nestable3">
							<ol class="dd-list">
								@foreach($categories as $k => $category)
									<li class="dd-item dd3-item" data-id="{{ $category->id }}">
										<div class="dd-handle dd3-handle"></div>
										<div class="dd3-content">{{ $category->title }}</div>
										<input type="hidden" name="categories[]" value="{{ $category->id }}" />
										@if(!empty($category->subcategories))
											<ol class="dd-list">
												@foreach($category->subcategories as $kk => $sub_category)
													<li class="dd-item dd3-item" data-id="{{ $sub_category->id }}">
														<div class="dd-handle dd3-handle"></div>
														<div class="dd3-content">{{ $sub_category->title }}</div>
														<input type="hidden" name="categories[]" value="{{ $sub_category->id }}" />
														@if(!empty($sub_category->subcategories))
															<ol class="dd-list">
																@foreach($sub_category->subcategories as $kkk => $step3)
																	<li class="dd-item dd3-item" data-id="{{ $step3->id }}">
																		<div class="dd-handle dd3-handle"></div>
																		<div class="dd3-content">{{ $step3->title }}</div>
																		<input type="hidden" name="categories[]" value="{{ $step3->id }}" />
																		@if(!empty($step3->subcategories))
																			<ol class="dd-list">
																				@foreach($step3->subcategories as $kkkk => $step4)
																						<li class="dd-item dd3-item" data-id="{{ $step4->id }}">
																							<div class="dd-handle dd3-handle"></div>
																							<div class="dd3-content">{{ $step4->title }}</div>
																							<input type="hidden" name="categories[]" value="{{ $step4->id }}" />
																							@if(!empty($step4->subcategories))
																								<ol class="dd-list">
																									@foreach($step4->subcategories as $kkkkk => $step5)
																										<li class="dd-item dd3-item" data-id="{{ $step5->id }}">
																											<div class="dd-handle dd3-handle"></div>
																											<div class="dd3-content">{{ $step5->title }}</div>
																											<input type="hidden" name="categories[]" value="{{ $step5->id }}" />
																											@if(!empty($step5->products))
																												<ol class="dd-list">
																													@foreach($step5->products as $p => $product)
																														<li class="dd-item dd3-item" data-id="{{ $product->id }}">
																															<div class="dd-handle dd3-handle"></div>
																															<div class="dd3-content">{{ $product->title }}</div>
																															<input type="hidden" name="products[]" value="{{ $product->id }}" />
																														</li>
																													@endforeach
																												</ol>
																											@endif
																											@if(!empty($step5->products))
																												<ol class="dd-list">
																													@foreach($step5->products as $p => $product)
																														<li class="dd-item dd3-item" data-id="{{ $product->id }}">
																															<div class="dd-handle dd3-handle"></div>
																															<div class="dd3-content">Code : {{ $product->code }} - Name: {{ Str::limit($product->getTranslation(Session::get('language') ?? 'en', 'title') ?? $product->name, 80, '...')  }}</div>
																															<input type="hidden" name="products[]" value="{{ $product->id }}" />
																														</li>
																													@endforeach
																												</ol>
																											@endif
																										</li>
																									@endforeach
																								</ol>
																							@endif
																							
																							@if(!empty($step4->products))
																								<ol class="dd-list">
																									@foreach($step4->products as $p => $product)
																										<li class="dd-item dd3-item" data-id="{{ $product->id }}">
																											<div class="dd-handle dd3-handle"></div>
																											<div class="dd3-content">Code : {{ $product->code }} - Name: {{ Str::limit($product->getTranslation(Session::get('language') ?? 'en', 'title') ?? $product->name, 80, '...')  }}</div>
																											<input type="hidden" name="products[]" value="{{ $product->id }}" />
																										</li>
																									@endforeach
																								</ol>
																							@endif
																						</li>
																				@endforeach
																			</ol>
																		@endif
																		@if(!empty($step3->products))
																			<ol class="dd-list">
																				@foreach($step3->products as $p => $product)
																					<li class="dd-item dd3-item" data-id="{{ $product->id }}">
																						<div class="dd-handle dd3-handle"></div>
																						<div class="dd3-content">Code : {{ $product->code }} - Name: {{ Str::limit($product->getTranslation(Session::get('language') ?? 'en', 'title') ?? $product->name, 80, '...')  }}</div>
																						<input type="hidden" name="products[]" value="{{ $product->id }}" />
																					</li>
																				@endforeach
																			</ol>
																		@endif
																	</li>
																@endforeach
															</ol>
														@endif
														@if(!empty($sub_category->products))
															<ol class="dd-list">
																@foreach($sub_category->products as $p => $product)
																	<li class="dd-item dd3-item" data-id="{{ $product->id }}">
																		<div class="dd-handle dd3-handle"></div>
																		<div class="dd3-content">Code : {{ $product->code }} - Name: {{ Str::limit($product->getTranslation(Session::get('language') ?? 'en', 'title') ?? $product->name, 80, '...')  }}</div>
																		<input type="hidden" name="products[]" value="{{ $product->id }}" />
																	</li>
																@endforeach
															</ol>
														@endif
													</li>
												@endforeach
											</ol>
										@endif
										@if(!empty($category->products))
											<ol class="dd-list">
												@foreach($category->products as $p => $product)
													<li class="dd-item dd3-item" data-id="{{ $product->id }}">
														<div class="dd-handle dd3-handle"></div>
														<div class="dd3-content">Code : {{ $product->code }} - Name: {{ Str::limit($product->getTranslation(Session::get('language') ?? 'en', 'title') ?? $product->name, 80, '...')  }}</div>
														<input type="hidden" name="products[]" value="{{ $product->id }}" />
													</li>
												@endforeach
											</ol>
										@endif
									</li>
								@endforeach
							</ol>
						</div>
					</div>
					<button class="btn btn-primary mb-3" type="submit">Submit</button>
				</form>
            </div>
        </div>
    </div>
    @push('footer')
		<script src="{{ asset('assets/js/backend/nestable.js') }}"></script>
        <script type="text/javascript">
			
			var updateOutput = function(e) {
				var list = e.length ? e : $(e.target),
					output = list.data('output');
				if (window.JSON) {
					output.val(window.JSON.stringify(list.nestable('serialize')));
				} else {
					output.val('JSON browser support required for this demo.');
				}

				// Update order_number attribute based on the Nestable order
				list.find('.dd-item').each(function(index) {
					$(this).data('order_number', index + 1);
				});
			};

			$('#nestable3').nestable({
				group: 1
			}).on('change', function(e) {
				updateOutput($(e.target));
			}).nestable('collapseAll');

			// Output initial serialized data
			updateOutput($('#nestable3').data('output', $('#nestable-output')));

			$('#nestable-menu').on('click', function(e) {
				var action = $(e.target).data('action');
				if (action === 'expand-all' || action === 'collapse-all') {
					$('#nestable3').nestable(action + 'All');
				}
			});
				
			
        </script>
    @endpush
@endsection
@extends('frontend.layout.app')
@section('content')
    <div id="catelogues">
        <div class="breadcrumb__nk">
            <div class="container"><a href="{{ route('home') }}" class="text-light">{{ trans('language.home') }} </a> / {{ trans('language.form') }}</div>
        </div>
        <div class="container text-center">
            <h1 class="page_title">{{ trans('language.form') }}</h1>
        </div>
        <div class="container catelogues_page__wrapper row">
            <div class="catelogues__products container col-12 col-md-12">
                <div class="" id="">
					<ul class="nav nav-tabs custom_nav" id="myTab" role="tablist">
						@foreach($brands as $brand)
							<li class="nav-item" role="presentation">
								<button class="nav-link @if($loop->iteration == 1) active @endif" id="{{ $brand->id }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $brand->id }}" type="button" role="tab" aria-controls="{{ $brand->id }}" aria-selected="true">{{ $brand->title }}</button>
							</li>
						@endforeach
					</ul>
					<div class="tab-content mt-4" id="myTabContent">
						@foreach($brands as $brand)
							<div class="tab-pane fade @if($loop->iteration == 1) show active @endif" id="{{ $brand->id }}" role="tabpanel" aria-labelledby="{{ $brand->id }}-tab">
								<div class="row">
									@php
										$catalogues = \App\Models\Catalogue::where('type','form')->where('brand_id', $brand->id)->where('status', 1)->get();
									@endphp
									@foreach($catalogues as $catalogue)
										<div class="col-lg-12">
											<div class="d-flex flex-sm-row flex-column">
												<div class="col-12 col-lg-5">
													<a href="{{ asset('uploads/catalogue-files/'.$catalogue->file)}}" target="_blank">
														<img src="{{ asset('uploads/catalogue-images/'.$catalogue->thumbnail) }}" class="">
													</a>
												</div>
												<div class="col-12 col-lg-7 m-2">
													<a href="{{ route('forms.details', $catalogue->slug) }}" >
														<h4 class="text-uppercase" title="{{ $catalogue->title }}">{{ $catalogue->title }}</h4>
													</a>
												</div>
											</div>
										</div>
									@endforeach
								</div>
							</div>
						@endforeach
					</div>
                </div>
            </div>
        </div>
    </div>
    @push('footer')
        <script type="text/javascript">
           
        </script>
    @endpush
@endsection

@extends('frontend.layout.app')
@section('content')
    <div id="sub-category">
        <div class="breadcrumb__nk">
            <div class="container"><a href="{{ route('home') }}" class="text-light">{{ trans('language.home') }} </a> / <a href="{{ route('categories') }}" class="text-light">{{ trans('language.categories') }}</a> / {{ $currentCategory->getTranslation(Session::get('language') ?? 'en', 'title') ?? $currentCategory->title }}</div>
        </div>
        <div class="container sub-category_page__wrapper">
            <div>
                <h1 class="page_title">{{ $currentCategory->getTranslation(Session::get('language') ?? 'en', 'title') ?? $currentCategory->title }}</h1>
                <div class="sub-category__products__wrapper">
                    <div class="product-category__card-container">
                        @foreach ($subCategories as $subCategory)
                            <div class="card">
                                <a href="{{ url('category/'.$subCategory->slug) }}">
                                    <img src="@if($subCategory->image) {{ asset('uploads/category-images/'.$subCategory->image) }} @else {{ asset('assets/img/no-img.jpg') }} @endif" alt="">
                                    <p class="text-uppercase">{{ $subCategory->getTranslation(Session::get('language') ?? 'en', 'title') ?? $subCategory->title }}</p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
		@if(isset($products))
			<div class="container">
				<h1 class="page_title">{{ trans('language.products') }}</h1>
				<form action="{{ route('search.product.by.category') }}" method="Post" class="sub-category__form m-auto" id="productSearchForm">
					<div class="form-floating mb-3 w-100">
						<input type="text" class="form-control" id="floatingInput" placeholder="{{ trans('language.label_name') }}" name="name">
						<label for="floatingInput">{{ trans('language.label_name') }}</label>
					</div>
					<div class="form-floating mb-3 w-100">
						<input type="text" class="form-control" id="floatingInput" placeholder="{{ trans('language.label_model') }}" name="model">
						<label for="floatingInput">{{ trans('language.label_model') }}</label>
					</div>
					<button type="submit" id="searchBtn" class="btn w-100">{{ trans('language.btn_search') }}</button>
				</form>
				<div class="" id="categoryProducts">
					
				</div>
				<div class="product-pagination pt-4" >
					<nav aria-label="Page navigation example">
						<ul class="pagination justify-content-center" id="product_pagination">
							
						</ul>
					</nav>
				</div>
			</div>
		@endif
    </div>

    @push('footer')
        <script type="text/javascript">
            function getProducts(url){
                let post_url = '';
                let id = '{{$currentCategory->id}}';
                let form = document.getElementById('productSearchForm');
                var formData = new FormData(form);
                formData.append('category_id', id);
                if(url == null){
                    post_url = $('#productSearchForm').attr('action');
                }else{
                    post_url = url;
                }
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: post_url,
                    type: "Post",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function (response) {
                        $('#categoryProducts').html(response.products_html);
                        $('#product_pagination').html(response.pagination_html);
                    }
                })
            }

            getProducts(null);

            $(document).on('click', '#searchBtn', function(e) {
                e.preventDefault();
                getProducts(null);
            })

            $(document).on('click', '.pagination_btn', function(e) {
                e.preventDefault();
                let url = $(this).attr('href');
                getProducts(url);
				$('html, body').scrollTop($(".sub-category__products").offset().top - 100);
            })
			
			$(document).on('submit', '#productSearchForm', function(e) {
				e.preventDefault();
                getProducts(null);
			})
        </script>
    @endpush
@endsection

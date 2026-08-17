@extends('frontend.layout.app')
@section('content')
    <div id="products">
        <div class="breadcrumb__nk">
            <div class="container"><a href="{{ route('home') }}" class="text-light">{{ trans('language.home') }} </a> / {{ trans('language.parts') }}</div>
        </div>
        <div class="products_title_container container">
            <div></div>
            <div>
                <h1 class="page_title">{{ trans('language.parts') }}</h1>
            </div>
        </div>
        <form action="" class="products__form  container d-flex d-md-none" id="partSearchForm">
            <div class="form-floating mb-3 w-100">
                <input type="text" class="form-control" id="floatingInput" placeholder="{{ trans('language.label_name') }}"
                    name="name">
                <label for="floatingInput">{{ trans('language.label_name') }}</label>
            </div>
            <div class="form-floating mb-3 w-100">
                <input type="email" class="form-control" id="floatingInput" placeholder="{{ trans('language.label_model') }}"
                    name="model">
                <label for="floatingInput">{{ trans('language.label_model') }}</label>
            </div>
            <button type="submit" id="searchBtn" class="btn w-100">{{ trans('language.btn_search') }}</button>
        </form>
        <div class="container products_page__wrapper">
            <div class="products__menu">
                <span class="filter__text">{{ trans('language.filter') }}</span>
                <div class="accordion" id="accordionExample">
                    <form action="">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseCategory" aria-expanded="true" aria-controls="collapseCategory">
                                    {{ trans('language.categories') }}
                                </button>
                            </h2>
                            <div id="collapseCategory" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    @foreach($categories as $category)
                                        <div class="form-check">
                                            <input class="form-check-input category_for_filter" type="checkbox" value="{{$category->id}}"
                                                id="category{{$category->id}}" name="category">
                                            <label class="form-check-label" for="category{{$category->id}}">
                                                {{ $category->title }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    {{ trans('language.brands') }}
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    @foreach($brands as $brand)
                                        <div class="form-check">
                                            <input class="form-check-input brands_for_filter" type="checkbox" value="{{$brand->id}}"
                                                id="brand{{$brand->id}}" name="brand[]">
                                            <label class="form-check-label" for="brand{{$brand->id}}">
                                                {{ $brand->title }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @foreach($filter_attributes as $attributes)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ trim(str_replace(' ', '', $attributes->attribute_name))}}" aria-expanded="true" aria-controls="collapse{{ trim(str_replace(' ', '', $attributes->attribute_name))}}">
                                        {{ $attributes->attribute_name }}
                                    </button>
                                </h2>
                                <div id="collapse{{ trim(str_replace(' ', '', $attributes->attribute_name))}}" class="accordion-collapse collapse">
                                    <div class="accordion-body">
                                        @foreach($attributes->attributes_values as $value)
                                            <div class="form-check">
                                                <input class="form-check-input attributes_for_filter" type="checkbox" value="{{ $value->value }}"
                                                    id="{{ trim(str_replace(' ', '', $value->value))}}">
                                                <label class="form-check-label" for="{{ trim(str_replace(' ', '', $value->value))}}">
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
            <div class="products__products container">
                <form action="" class="products__form d-none d-md-flex" id="partSearchForm">
                    <div class="form-floating mb-3 w-100">
                        <input type="text" class="form-control" id="floatingInput" placeholder="{{ trans('language.label_name') }}"
                            name="name">
                        <label for="floatingInput">{{ trans('language.label_name') }}</label>
                    </div>
                    <div class="form-floating mb-3 w-100">
                        <input type="email" class="form-control" id="floatingInput" placeholder="{{ trans('language.label_model') }}"
                            name="model">
                        <label for="floatingInput">{{ trans('language.label_model') }}</label>
                    </div>
                    <button type="submit" id="searchBtn" class="btn w-100">{{ trans('language.btn_search') }}</button>
                </form>

                <div class="p-2" id="productListing">

                </div>
                <div class="product-pagination pt-4" >
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center" id="product_pagination">
                            
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    @push('footer')
        <script type="text/javascript">
            function getParts(url = null){
                $('body').addClass('loader-open');
                let form = document.getElementById('partSearchForm');
                var formData = new FormData(form);

                $('.category_for_filter').each(function() {
                    if ($(this).is(':checked')) {
                        var category_for_filter = $(this).val();
                        formData.append('category_for_filter[]', category_for_filter);
                    }
                });

                $('.brands_for_filter').each(function() {
                    if ($(this).is(':checked')) {
                        var brands_for_filter = $(this).val();
                        formData.append('brands_for_filter[]', brands_for_filter);
                    }
                });


                $('.attributes_for_filter').each(function() {
                    if ($(this).is(':checked')) {
                        var attributes_for_filter = $(this).val();
                        formData.append('attributes_for_filter[]', attributes_for_filter);
                    }
                });
				
				if(url == null){
                    post_url = "{{ route('search.parts') }}";
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
                        $('body').removeClass('loader-open');
                        $('#productListing').html(response.products_html);
                        $('#product_pagination').html(response.pagination_html);
                    }
                })
            }

            getParts();

            $(document).on('click', '#searchBtn', function(e) {
                e.preventDefault();
                getParts();
            })


            $(document).on('change', '.attributes_for_filter', function(e) {
                e.preventDefault();
                getParts();
            })

            $(document).on('change', '.category_for_filter', function(e) {
                e.preventDefault();
                getParts();
            })

            $(document).on('change', '.brands_for_filter', function(e) {
                e.preventDefault();
                getParts();
            })
			
			 $(document).on('click', '.pagination_btn', function(e) {
                e.preventDefault();
                let url = $(this).attr('href');
                getParts(url);
				$('html, body').scrollTop($("#products").offset().top - 100);
            })

        </script>
    @endpush
@endsection

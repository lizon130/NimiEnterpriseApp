@extends('frontend.layout.app')
@section('content')
    <div id="catelogues">
        <div class="breadcrumb__nk">
            <div class="container"><a href="{{ route('home') }}" class="text-light">{{ trans('language.home') }} </a> / {{ trans('language.catalogues') }}</div>
        </div>
        <div class="catelogues_title_container container">
            <div></div>
            <div>
                <h1 class="page_title">{{ trans('language.catalogues') }}</h1>
            </div>
        </div>
        <form action="" class="catelogues__form d-flex d-md-none container" id="catalogue_search_form_for_long">
            <div class="form-floating mb-3 w-100">
                <input type="text" class="form-control search_text" id="search_text" placeholder="Name" name="name">
                <label for="floatingInput">{{ trans('language.label_name') }}</label>
            </div>
            <button type="submit" class="btn" id="searchBtn">{{ trans('language.btn_search') }}</button>
        </form>
        <div class="container catelogues_page__wrapper row">
            <div class="catelogues__menu col-12 col-md-3">
                <span class="filter__text">{{ trans('language.filter') }}</span>
                <div class="accordion" id="accordionExample">
                    <form action="">
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
                                            <input class="brand_search_checkbox form-check-input" type="checkbox" value="{{ $brand->id }}" name="brand[]" id="brand-{{ $brand->id }}">
                                            <label class="form-check-label" for="brand-{{ $brand->id }}">
                                                {{$brand->title}}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
						
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                    {{ trans('language.categories') }}
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse ">
                                <div class="accordion-body">
                                    @foreach($categories as $category)
                                        <div class="form-check">
                                            <input class="category_search_checkbox form-check-input" type="checkbox" value="{{ $category->id }}" name="category[]" id="category-{{ $category->id }}">
                                            <label class="form-check-label" for="category-{{ $category->id }}">
                                                {{ $category->title }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="catelogues__products container col-12 col-md-9">
                <form action="" class="catelogues__form d-none d-md-flex" id="catalogue_search_form">
                    <div class="form-floating mb-3 w-100">
                        <input type="text" class="form-control search_text" id="search_text" placeholder="Name" name="name">
                        <label for="floatingInput">{{ trans('language.label_name') }}</label>
                    </div>
                    <button type="button" class="btn" id="searchBtn">{{ trans('language.btn_search') }}</button>
                </form>
                <div class="catelogues__card-container" id="catelogue_list">

                </div>
            </div>
        </div>
    </div>
    @push('footer')
        <script type="text/javascript">
            function getCatalogue(){
                $('body').addClass('loader-open');
				let title = '';
				if($('#catalogue_search_form .search_text').val() != ''){
					title = $('#catalogue_search_form .search_text').val();
				}else{
					title = $('#catalogue_search_form_for_long .search_text').val();
				}
				let brands = [];
                $('.brand_search_checkbox:checked').each(function() {
                    brands.push($(this).val());
                });

                let categories = [];
                $('.category_search_checkbox:checked').each(function() {
                    categories.push($(this).val());
                });
				
                $.ajax({
                    url: "{{ url('search-catalogue') }}",
                    type: "Get",
                    data: {
                        title: title,
                        category: categories,
                        brand: brands
                    },
                    dataType: "html",
                    success: function (response) {
                        $('body').removeClass('loader-open');
                        $('#catelogue_list').html(response);
                    }
                })
            }

            getCatalogue();

            $(document).on('click', '#searchBtn', function(e) {
                e.preventDefault();
                getCatalogue();
            })

            $(document).on('change', '.brand_search_checkbox', function(e) {
                e.preventDefault();
                getCatalogue();
            })

            $(document).on('change', '.category_search_checkbox', function(e) {
                e.preventDefault();
                
                getCatalogue();
            })
			
			$(document).on('submit', '#catalogue_search_form', function(e) {
				e.preventDefault();
                getCatalogue();
			})
			
			$(document).on('submit', '#catalogue_search_form_for_long', function(e) {
				e.preventDefault();
                getCatalogue();
			})
        </script>
    @endpush
@endsection

@extends('frontend.layout.app')
@section('content')
    <div id="newsPage">
        <div class="breadcrumb__nk">
            <div class="container"><a href="{{ route('home') }}" class="text-light">{{ trans('language.home') }} </a> / {{ trans('language.news') }}</div>
        </div>
        <div class="news_title_container container">
            <div></div>
            <div>
                <h1 class="page_title">{{ trans('language.news') }}</h1>
            </div>
        </div>
        <div class="container news_page__wrapper row">
            <div class="news__menu col-12 col-md-3">
                <span class="filter__text">{{ trans('language.filter') }}</span>
                <div class="accordion" id="accordionExample">
                    <form action="">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    {{ trans('language.archives') }}
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    @foreach ($months as $month)
										@php
											$date = \Carbon\Carbon::createFromFormat('n-Y', $month->month_year);
										@endphp
                                        <div class="form-check">
                                            <input class="form-check-input search_checkbox" type="checkbox" value="{{ $date->format('Y') }}-{{ $date->format('m') }}-01" id="news_lang{{ $date->format('F Y') }}" name="year">
                                            <label class="form-check-label" for="news_lang{{ $date->format('F Y') }}">
                                                {{ $date->format('F Y') }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
						
						<div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseCategory" aria-expanded="true" aria-controls="collapseCategory">
                                    {{ trans('language.categories') }}
                                </button>
                            </h2>
                            <div id="collapseCategory" class="accordion-collapse collapse">
                                <div class="accordion-body">
									<div class="form-check">
										<input class="form-check-input category_for_filter" type="checkbox" value="News" id="categoryNews" name="category">
										<label class="form-check-label" for="categoryNews">
											News
										</label>
									</div>
									<div class="form-check">
										<input class="form-check-input category_for_filter" type="checkbox" value="Media" id="categoryMedia" name="category">
										<label class="form-check-label" for="categoryMedia">
											Media
										</label>
									</div>
									<div class="form-check">
										<input class="form-check-input category_for_filter" type="checkbox" value="Promotions" id="categoryPromotions" name="category">
										<label class="form-check-label" for="categoryPromotions">
											Promotions
										</label>
									</div>
									<div class="form-check">
										<input class="form-check-input category_for_filter" type="checkbox" value="Show" id="categoryShow" name="category">
										<label class="form-check-label" for="categoryShow">
											Show
										</label>
									</div>
									<div class="form-check">
										<input class="form-check-input category_for_filter" type="checkbox" value="Uncategorized" id="categoryUncategorized" name="category">
										<label class="form-check-label" for="categoryUncategorized">
											Uncategorized
										</label>
									</div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="news__products container col-12 col-md-9">
                <form action="" class="news__form" id="title_search_form">
                    <div class="form-floating mb-3 w-100">
                        <input type="text" class="form-control" id="search_text" placeholder="{{ trans('language.label_name') }}" name="title">
                        <label for="floatingInput">{{ trans('language.label_name') }}</label>
                    </div>
                    <button type="submit" id="searchBtn" class="btn">{{ trans('language.btn_search') }}</button>
                </form>
                <div class="news__container row m-auto" id="news_container">
                    
                </div>
            </div>
        </div>
    </div>
    @push('footer')
        <script type="text/javascript">
            function getNews(title = null, year = null, category =null){
				$('body').addClass('loader-open');
                var formData = new FormData();
				formData.append('title', $('#title_search_form #search_text').val());
				$('.search_checkbox').each(function() {
                    if ($(this).is(':checked')) {
                        var year = $(this).val();
                        formData.append('year[]', year);
                    }
                });

                $('.category_for_filter').each(function() {
                    if ($(this).is(':checked')) {
                        var category = $(this).val();
                        formData.append('category[]', category);
                    }
                });
		
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ url('search-news') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "html",
                    success: function (response) {
                        $('#news_container').html(response);
						$('body').removeClass('loader-open');
                    }
                })
            }

            getNews();

            $(document).on('click', '#searchBtn', function(e) {
                e.preventDefault();
                let title = $('#search_text').val();
                getNews(title);
            })

            $(document).on('change', '.search_checkbox', function(e) {
                e.preventDefault();
                let year = $(this).val();
                if(year != ''){
                    getNews('', year);
                }else{
                    getNews();
                }
                
            })
			
			$(document).on('change', '.category_for_filter', function(e) {
                e.preventDefault();
                let category = $(this).val();
                if(category != ''){
                    getNews('', '', category);
                }else{
                    getNews();
                }
                
            })
			
			// $(document).ready(function () {
			// 	function checkScreenSize() {
			// 		if ($(window).width() < 768) {
			// 			// If the screen width is less than 768 pixels, hide the element
			// 			$(".accordion-collapse").removeClass("show");
			// 			$(".accordion-button").addClass("collapsed");
			// 			$(".accordion-button").attr("aria-expanded", "false");
			// 		} else {
			// 			// If the screen width is 768 pixels or more, show the element
			// 			$(".accordion-collapse").addClass("show");
			// 			$(".accordion-button").removeClass("collapsed");
			// 			$(".accordion-button").attr("aria-expanded", "true");
			// 		}
			// 	}

			// 	// Call the function on page load
			// 	checkScreenSize();

			// 	// Call the function when the window is resized
			// 	$(window).resize(function () {
			// 		checkScreenSize();
			// 	});
			// })
        </script>
    @endpush
@endsection

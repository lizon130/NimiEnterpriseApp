@extends('frontend.layout.app')
@push('header')
	<script defer src="{{ asset('assets/pdfviewer/js/flipbook.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/pdfviewer/css/flipbook.style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/pdfviewer/css/font-awesome.css') }}">

@endpush
@section('content')
    <div id="catelogues">
        <div class="breadcrumb__nk">
            <div class="container"><a href="{{ route('home') }}" class="text-light">{{ trans('language.home') }} </a> / <a href="{{ route('catalogues') }}" class="text-light">{{ trans('language.catalogues') }}</a> / {{ $catalogue->title }} </div>
        </div>
        <div class="container pb-5">
            <h1 class="page_title text-start">{{ $catalogue->title }}</h1>
            <div class="row position-relative">
                <div id="container" class="col-lg-12 h-100"></div>
				<div id="pdf">
					<a href="{{ url('calculator') }}" class="calculator-btn" target="myWindow" onclick="window.open(this.href, 'myWindow', 'width=500,height=400'); return false;"><i class="fa-solid fa-calculator"></i></a>
				</div>
            </div>
        </div>
    </div>
    @push('footer')
        <script type="text/javascript">
            $(document).ready(function () {
                $("#container").flipBook({
                    pdfUrl:"{{ asset('uploads/catalogue-files/'.$catalogue_files)}}",
                    btnSearch: {
                        enabled: true,
                        title: "Search",
                        icon: "fas fa-search"

                    },
                    btnSelect:{
                        enabled: false,
                    },
                    btnBookmark:{
                        enabled: false,
                    },
                    btnShare:{
                        enabled: false,
                    },
                    btnDownloadPages:{
                        enabled: false,
                    },
                    btnSound:{
                        enabled: false,
                    }
                });
            })
        </script>
    @endpush
@endsection

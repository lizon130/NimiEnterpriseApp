@extends('frontend.layout.app')
@section('content')
    <div id="">
        <div class="breadcrumb__nk">
            <div class="container"><a href="{{ route('home') }}" class="text-light">{{ trans('language.home') }} </a> / {{ ucwords(trim(str_replace('-',' ',$slug))) }}</div>
        </div>
        <div class="container">
            <h1 class="page_title text-center">{{ ucwords(trim(str_replace('-',' ',$slug))) }}</h1>
        </div>
        <div class="container">
            {!! $content !!}
        </div>
    </div>
    @push('footer')
        <script type="text/javascript">
            
        </script>
    @endpush
@endsection

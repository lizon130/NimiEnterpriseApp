@extends('frontend.layout.app')
@section('content')
    <div id="servicesPage">
        <div class="breadcrumb__nk">
            <div class="container">{{ trans('language.home') }} / {{ trans('language.service') }}</div>
        </div>
        <h1 class="page_title">{{ trans('language.service') }}</h1>
        <div class="container">
            <div class="row d-flex align-items-stretch">
                @foreach ($services as $item)
                    <a class="col-12 col-sm-6 col-md-4 col-lg-3" href="{{ route('service.details', $item->id) }}">
                        <div class="card text-center">
                            <div class="img__hover--effect">
                                <img class="img-fluid" src="{{ asset('uploads/service-images/' . $item->media) }}"
                                    alt="">
                            </div>
                            <p class="text-uppercase custom-tooltip" title="{{ $item->getTranslation(Session::get('language') ?? 'en', 'title') ?? $item->title }}">
    {{ Str::limit($item->getTranslation(Session::get('language') ?? 'en', 'title') ?? $item->title, 100, '...') }}
</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection

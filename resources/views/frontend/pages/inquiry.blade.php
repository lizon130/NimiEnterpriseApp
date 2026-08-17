@extends('frontend.layout.app')
@section('content')
<div id="inquiry">
    <div class="breadcrumb__nk">
        <div class="container"><a href="{{ route('home') }}" class="text-light">{{ trans('language.home') }} </a> / {{ trans('language.label_inquiry') }}</div>
    </div>
    <div class="container inquiry__container">
        <h1 class="page_title">{{ trans('language.label_inquiry_list') }}</h1>
        @if(session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session()->get('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(count($inquirylistItems) > 0)
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Product Code</th>
                        <th scope="col">Unit</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inquirylistItems as $item)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{ $item['product']['name']}}</td>
                            <td>{{ $item['product']['code']}}</td>
                            <td class="inquiry__container__table--unit">
                                <span><a href="{{ route('decrement.from.inquiry', $item['product']['id'])}}"><i class="fa-solid fa-minus"></i></a></span>
                                <span>{{ $item['quantity'] }}</span>
                                <span><a href="{{ route('increment.from.inquiry', $item['product']['id'])}}"><i class="fa-solid fa-plus"></i></a></span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('remove.from.inquiry', $item['product']['id'])}}" class="text-danger"><i class="fa-solid fa-trash-can"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="inquiry__container--btn">
                <a href="{{route('products')}}" class="btn inquiry__container__btn--continue">{{ trans('language.btn_continue_shipping') }}</a>
                <a href="{{ url('inquiry/request') }}" class="btn inquiry__container__btn--request">{{ trans('language.btn_request_inquiry') }}</a>
            </div>
        @else
            <p>{{ trans('language.label_no_product_on_inquiry_list') }}!</p>
        @endif
    </div>
</div>
@endsection

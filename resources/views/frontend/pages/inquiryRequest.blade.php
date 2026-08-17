@extends('frontend.layout.app')
@section('content')
<div id="inquiryRequest">
    <div class="breadcrumb__nk">
        <div class="container"><a href="{{ route('home') }}" class="text-light">{{ trans('language.home') }} </a> / {{ trans('language.label_inquiry') }}</div>
    </div>
    <div class="container inquiry__container">
        <h1 class="page_title">{{ trans('language.inquiry_request') }}</h1>
        @if(session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session()->get('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger w-75 m-auto">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{ route('inquiry.request.send') }}" class="inquiryRequest__form">
            @csrf
            <div class="d-flex gap-2 mb-1 w-100">
                <div class="form-floating mb-1 w-100">
                    <input type="text" class="form-control" id="floatingInput" placeholder="{{ trans('language.label_company_name') }}" name="company" required>
                    <label for="floatingInput">{{ trans('language.label_company_name') }} <span class="text-danger">*</span></label>
                </div>
                <div class="form-floating w-100">
                    <input type="text" class="form-control" id="floatingInput" placeholder="{{ trans('language.label_contact_name') }}" name="name" required>
                    <label for="floatingInput">{{ trans('language.label_contact_name') }} <span class="text-danger">*</span></label>
                </div>
            </div>
            <div class="d-flex gap-2 mb-1 w-100">
                <div class="form-floating mb-1 w-100">
                    <input type="text" class="form-control" id="floatingInput" placeholder="{{ trans('language.label_phone') }}" name="phone" required>
                    <label for="floatingInput">{{ trans('language.label_phone') }} <span class="text-danger">*</span></label>
                </div>
                <div class="form-floating w-100">
                    <input type="email" class="form-control" id="floatingInput" placeholder="{{ trans('language.email') }}" name="email" required>
                    <label for="floatingInput">{{ trans('language.email') }} <span class="text-danger">*</span></label>
                </div>
            </div>
            <div class="d-flex gap-2 mb-1 w-100">
                <div class="form-floating mb-1 w-100">
                    <input type="text" class="form-control" id="floatingInput" placeholder="{{ trans('language.label_address') }}" name="address" required>
                    <label for="floatingInput">{{ trans('language.label_address') }} <span class="text-danger">*</span></label>
                </div>
                <div class="form-floating mb-1 w-100">
                    <input type="text" class="form-control" id="floatingInput" placeholder="{{ trans('language.label_postal_code') }}" name="post_code" required>
                    <label for="floatingInput">{{ trans('language.label_postal_code') }} <span class="text-danger">*</span></label>
                </div>
            </div>
            <div class="d-flex gap-3 mb-1 w-100">
                <div class="form-floating w-50">
                    <input type="text" class="form-control" id="floatingInput" placeholder="{{ trans('language.label_city') }}" name="city" required>
                    <label for="floatingInput">{{ trans('language.label_city') }} <span class="text-danger">*</span></label>
                </div>
                <div class="form-floating w-50">
                    <input type="text" class="form-control" id="floatingInput" placeholder="{{ trans('language.label_state') }}" name="state" required>
                    <label for="floatingInput">{{ trans('language.label_state') }}</label>
                </div>
                <div class="form-floating w-50">
                    <input type="text" class="form-control" id="floatingInput" placeholder="{{ trans('language.label_country') }}" name="country" required>
                    <label for="floatingInput">{{ trans('language.label_country') }} <span class="text-danger">*</span></label>
                </div>
            </div>
            <div class="form-floating w-100">
                <textarea class="form-control" id="" cols="30" rows="5" placeholder="Write something" name="note"></textarea>
                <label for="floatingInput">{{ trans('language.label_note') }}(Optional)</label>
            </div>
            <div class="d-flex justify-content-between align-items-center inquryRequest__form--submit">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"  required>
                    <label class="form-check-label" for="flexCheckDefault">
                        {{ trans('language.i_have_read_and_agree') }} <a href="{{ route('page', 'terms-and-conditions') }}">{{ trans('language.terms_condition') }}</a>, <a href="{{ route('page', 'privacy-policy') }}">{{ trans('language.privacy_policy') }}</a> and <a href="{{ route('page', 'return-policy') }}">{{ trans('language.return_policy') }}</a>
                    </label>
                </div>
                <button type="submit" class="btn">{{ trans('language.btn_request_inquiry') }}</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="orderConfirm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg order__confirm--modal">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Thank You
                    For Visiting</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="modal-subtitle">Your inquiry request has been sent successfully!</h6>
            </div>
            <div class="modal-footer">
                <a href="{{ url('products') }}" class="btn">Go To Products</a>
            </div>
        </div>
    </div>
</div>

@push('footer')
    <script type="text/javascript">
        $(document).ready(function () {
            @if(session()->has('message'))
                $('#orderConfirm').modal('show');
            @endif
        })
    </script>
@endpush
@endsection

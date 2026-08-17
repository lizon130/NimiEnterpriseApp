@extends('frontend.layout.app')
@section('content')
<div id="order">
    <div class="breadcrumb__nk">
        <div class="container"><a href="{{ route('home') }}" class="text-light">{{ trans('language.home') }} </a> / {{ trans('language.service_order') }}</div>
    </div>
    <div class="order__page--wrapper container">
        <h1 class="page_title text-center">{{ trans('language.service_order') }}</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session()->get('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('service.order.send')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="service__order--form">
                <div class="">
                    <h5 class="order__forms--subtitle ">{{ trans('language.billing_address') }}</h5>
                    <div class="row">
						<div class="col-lg-12 p-1">
                            <div class="form-floating mb-1 w-100">
                                <input type="text" class="form-control" placeholder="{{ trans('language.label_company_name') }}" name="company_name" value="{{old('company_name')}}" required>
                                <label for="floatingInput">{{ trans('language.label_company_name') }}<span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-lg-6 p-1">
                            <div class="form-floating mb-1 w-100">
                                <input type="text" class="form-control" placeholder="{{ trans('language.label_contact_name') }}" name="name" value="{{old('name')}}" required>
                                <label for="floatingInput">{{ trans('language.label_contact_name') }}<span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-lg-6 p-1">
                            <div class="form-floating mb-1 w-100">
                                <input type="email" class="form-control" placeholder="{{ trans('language.label_e_mail') }}" name="email" value="{{old('email')}}" required>
                                <label for="floatingInput">{{ trans('language.label_e_mail') }}<span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-lg-12 p-1">
                            <div class="form-floating mb-1 w-100">
                                <input type="text" class="form-control" placeholder="{{ trans('language.label_location') }}" name="address" value="{{old('address')}}" required>
                                <label for="floatingInput">{{ trans('language.label_location') }}<span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-lg-12 p-1">
                            <div class="form-floating mb-1 w-100">
                                <input type="file" class="form-control" name="file" >
                                <label for="floatingInput">File</label>
                            </div>
                        </div>
                        <div class="col-lg-12 p-1">
                            <div class="form-floating mb-1 w-100">
                                <textarea type="text" class="form-control" placeholder="{{ trans('language.label_note') }}" name="note" value="{{old('note')}}" ></textarea>
                                <label for="floatingInput">{{ trans('language.label_note') }}</label>
                            </div>
                        </div>
                        <div class="text-start">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                                <label class="form-check-label" for="flexCheckDefault">
                                    {{ trans('language.i_have_read_and_agree') }} <a href="{{ route('page', 'terms-and-conditions') }}">{{ trans('language.terms_condition') }}</a>, <a href="{{ route('page', 'privacy-policy') }}">{{ trans('language.privacy_policy') }}</a> and <a href="{{ route('page', 'return-policy') }}">{{ trans('language.return_policy') }}</a>
                                </label>
                            </div>
                            <button class="btn bg-theme text-light" type="submit" >{{ trans('language.btn_confirm_order') }}</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<div class="modal fade" id="orderConfirm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg order__confirm--modal">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Thank You
                    For Ordering</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="modal-subtitle">Your Order has been Successful</h6>
            </div>
            <div class="modal-footer">
                <a href="{{ url('services') }}" class="btn">Continue Shopping</a>
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

@extends('frontend.layout.app')
@push('header')
    <link rel="stylesheet" href="{{ asset('assets/build/css/intlTelInput.css') }}" />
@endpush
@section('content')

    <style>
        @media (max-width: 767px) {
            #phone {
                width: 342px;
            }
        }

        .error-tag {
            color: red;
        }
    </style>

    <div id="order">
        <div class="breadcrumb__nk">
            <div class="container"><a href="{{ route('home') }}" class="text-light">{{ trans('language.home') }} </a> /
                {{ trans('language.order') }}</div>
        </div>
        <div class="order__page--wrapper container">
            <h1 class="page_title">{{ trans('language.checkout') }}</h1>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Added a notice so the user knows the data was pulled automatically --}}
            @if (isset($company) && $company)
                <div class="alert alert-info">
                    Billing details have been automatically filled from your company profile.
                </div>
            @endif

            <form action="{{ url('/order-place') }}" method="post" class="" id="orderForm">
                @csrf
                <div class="col-md-6 m-auto">
                    <div class="order__forms--billing">
                        <h5 class="order__forms--subtitle">{{ trans('language.billing_address') }}</h5>
                        <div class="row">
                            <div class="col-lg-6 p-1">
                                <div class="form-floating mb-1 w-100">
                                    <input type="text" class="form-control input-field"
                                        placeholder="{{ trans('language.label_name') }}" name="name"
                                        value="{{ $company->contact_name ?? old('name') }}" required>
                                    <label for="floatingInput">{{ trans('language.label_name') }}<span
                                            class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-lg-6 p-1">
                                <div class="form-floating mb-1 w-100" id="order_phone_container">
                                    <input style="width: 307px; max-width: 342px;" id="phone" type="text"
                                        class="form-control" placeholder="{{ trans('language.label_phone') }}"
                                        name="phone" value="{{ $company->phone_number ?? old('phone') }}" required>
                                    <label for="floatingInput">{{ trans('language.label_phone') }}<span
                                            class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-lg-12 p-1">
                                <div class="form-floating mb-1 w-100">
                                    <input type="email" class="form-control"
                                        placeholder="{{ trans('language.label_e_mail') }}" name="email"
                                        value="{{ $company->email ?? old('email') }}" required>
                                    <label for="floatingInput">{{ trans('language.label_e_mail') }}<span
                                            class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-lg-12 p-1">
                                <div class="form-floating mb-1 w-100">
                                    <input type="text" class="form-control"
                                        placeholder="{{ trans('language.label_address') }}" name="address"
                                        value="{{ $company->address ?? old('address') }}" required>
                                    <label for="floatingInput">{{ trans('language.label_address') }}<span
                                            class="text-danger">*</span></label>
                                </div>
                            </div>

                            {{-- <div class="col-lg-6 p-1">
                            <div class="form-floating mb-1 w-100">
                                <input type="text" class="form-control" placeholder="{{ trans('language.label_postal_code') }}" name="post_code" value="{{ $company->post_code ?? old('post_code') }}">
                                <label for="floatingInput">{{ trans('language.label_postal_code') }}<span class="text-danger">*</span></label>
                            </div>
                        </div> --}}

                            {{-- <div class="col-lg-6 p-1">
                            <div class="form-floating mb-1 w-100">
                                <input type="text" class="form-control" placeholder="{{ trans('language.label_city') }}" name="city" value="{{ $company->city ?? old('city') }}" required>
                                <label for="floatingInput">{{ trans('language.label_city') }}<span class="text-danger">*</span></label>
                            </div>
                        </div> --}}

                            {{-- <div class="col-lg-6 p-1">
                            <div class="form-floating mb-1 w-100">
                                <input type="text" class="form-control" placeholder="{{ trans('language.label_state') }}" name="state" value="{{ $company->state ?? old('state') }}" required>
                                <label for="floatingInput">{{ trans('language.label_state') }}<span class="text-danger">*</span></label>
                            </div>
                        </div> --}}

                            {{-- <div class="col-lg-6 p-1">
                            <div class="form-floating mb-1 w-100">
                                <select class="form-select country" id="country" name="country" required>
                                    <option value="">-- Select Country -- </option>
                                    @foreach ($countrycodes as $country_name => $code)
                                        <option value="{{ $code }}" {{ (isset($company) && $company->country == $code) ? 'selected' : (old('country') == $code ? 'selected' : '') }}>{{ $country_name }}</option>
                                    @endforeach
                                </select>
                                <label for="floatingInput">{{ trans('language.label_country') }}<span class="text-danger">*</span></label>
                            </div>
                        </div> --}}

                            <div class="col-lg-12 p-1">
                                <div class="form-floating mb-1 w-100">
                                    <textarea type="text" class="form-control" placeholder="{{ trans('language.label_note') }}" name="note"
                                        id="order_note">{{ old('note') }}</textarea>
                                    <label for="floatingInput">{{ trans('language.label_note') }}</label>
                                </div>
                            </div>

                            <div class="col-lg-12 p-1">
                                <div class="form-group">
                                    <label for="">
                                        <input type="radio" value="cash_on_deliver" name="payment_method"
                                            class="payment_method" checked> Cash On Delivery
                                    </label>
                                    <label for="">
                                        <input type="radio" value="online_payment" name="payment_method"
                                            class="payment_method"> Online Payment
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 m-auto order__form--submit">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="agree_check" required>
                        <label class="form-check-label" for="agree_check">
                            {{ trans('language.i_have_read_and_agree') }} <a
                                href="{{ route('page', 'terms-and-conditions') }}">{{ trans('language.terms_condition') }}</a>,
                            <a href="{{ route('page', 'privacy-policy') }}">{{ trans('language.privacy_policy') }}</a> and
                            <a href="{{ route('page', 'return-policy') }}">{{ trans('language.return_policy') }}</a>
                        </label>
                        <small id="error_agree_check" class="d-none" style="color: red;">This field is required</small>
                    </div>
                    @if (Auth::user())
                        <button class="btn btn_confirm_purchase"
                            type="submit">{{ trans('language.btn_confirm_purchase') }}</button>
                        <button class="btn btn_confirm_purchase" type="button" id="payButtonId"
                            style="display:none;">{{ trans('language.btn_confirm_purchase') }}</button>
                    @else
                        <a class="btn" href="{{ url('login') }}">{{ trans('language.btn_confirm_purchase') }}</a>
                    @endif
                </div>
            </form>

        </div>
    </div>

    <div class="modal fade" id="orderConfirm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg order__confirm--modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Thank You For Ordering</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 class="modal-subtitle">Your Order has been Successful</h6>
                </div>
                <div class="modal-footer">
                    <a href="{{ url('products') }}" class="btn">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>

    @push('footer')
        <script src="{{ asset('assets/dist/rxp-js.js') }}"></script>
        <script>
            // get the HPP JSON from the server-side SDK
            $(document).ready(function() {
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $(document).on('click', '.btn_confirm_purchase', function(e) {
                    e.preventDefault();

                    if ($('#agree_check').prop('checked')) {
                        $('#agree_check').removeClass('check-border-color');
                        $('#error_agree_check').removeClass('d-block check-border-color').addClass('d-none');
                    } else {
                        $('#error_agree_check').removeClass('d-none').addClass('d-block check-border-color');
                        $('#agree_check').addClass('check-border-color');
                    }
                    if (!validateForm()) {
                        return;
                    }
                    if (!$('#agree_check').prop('checked')) {
                        return;
                    }

                    let payment_method = $('input[name=payment_method]:checked').val();

                    if (payment_method == 'online_payment') {
                        var formData = $("#orderForm").serialize();
                        let phone_prefix = $('#orderForm .iti__selected-dial-code').text();
                        formData += '&phone_prefix=' + phone_prefix;

                        var redirecturlWithToken = "{{ url('after-order') }}?_token=" + csrfToken;
                        var proxiurlWithToken = "{{ url('place-order') }}?slug=process-a-payment&" + formData;
                        $.getJSON(proxiurlWithToken, function(jsonFromRequestEndpoint) {
                            RealexHpp.setHppUrl("https://pay.sandbox.realexpayments.com/pay");
                            RealexHpp.lightbox.init("payButtonId", redirecturlWithToken,
                                jsonFromRequestEndpoint);
                            $('#payButtonId').click();
                        });
                    } else {
                        $('#orderForm').submit();
                    }
                })


                $(document).on('change', '.payment_method', function(e) {
                    e.preventDefault();
                    let payment_method = $(this).val();
                    if (payment_method != '' && payment_method == 'cash_on_deliver') {
                        $('#orderForm .btn_confirm_purchase').attr('id', '');
                        $('#orderForm .btn_confirm_purchase').attr('type', 'submit');
                        $('#orderForm').attr('action', '/order-place');
                        $('#orderForm .btn_confirm_purchase').removeAttr('onClick');
                    } else {
                        $('#orderForm .btn_confirm_purchase').attr('onClick', 'onlineConfirmPurchase(event);');
                        $('#orderForm .btn_confirm_purchase').attr('id', 'payButtonId');
                        $('#orderForm .btn_confirm_purchase').attr('type', 'button');
                        $('#orderForm').attr('action', '');
                    }
                });

                function validateForm() {
                    var isValid = true;
                    $('#orderForm :required').each(function() {
                        if ($(this).val() == '') {
                            $(this).css("border-color", "red");
                            if ($(this).next('.error-tag').length == 0) {
                                $(this).after('<small class="error-tag">This field is required</small>');
                            }
                            isValid = false;
                        } else {
                            $(this).css("border-color", "#d4d4d4");
                            $(this).next('.error-tag').remove();
                        }
                    });

                    return isValid;
                }
            });
        </script>

        <script type="text/javascript">
            $(document).ready(function() {
                @if (session()->has('message'))
                    $('#orderConfirm').modal('show');
                @endif
            })
        </script>

        <script src="{{ asset('assets/build/js/intlTelInput.js') }}"></script>

        <script>
            var input = document.querySelector("#phone");
            window.intlTelInput(input, {
                autoInsertDialCode: false,
                autoPlaceholder: "off",
                formatOnDisplay: false,
                separateDialCode: true,
                utilsScript: "assets/build/js/utils.js"
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const form = document.getElementById("orderForm");
                form.addEventListener("submit", function(event) {
                    const requiredFields = form.querySelectorAll(".input-field[required]");

                    for (let i = 0; i < requiredFields.length; i++) {
                        const inputValue = requiredFields[i].value.trim();
                        if (inputValue === "") {
                            event.preventDefault();
                            alert("Field Required: Please fill in all required fields.");
                            return;
                        }
                    }
                });
            });

            $(document).ready(function() {
                $(document).on('input', '#order_phone_container input', function() {
                    if ($(this).val()) {
                        $('#order_phone_container label').hide();
                    } else {
                        $('#order_phone_container label').show();
                    }
                })
            })
        </script>
    @endpush
@endsection

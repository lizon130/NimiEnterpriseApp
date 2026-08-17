@extends('frontend.layout.app')
@section('content')
<div id="wishlist">
    <div class="breadcrumb__nk">
        <div class="container"><a href="{{ route('home') }}" class="text-light">{{ trans('language.home') }} </a> / {{ trans('language.cart') }}</div>
    </div>
    <div class="container go_back_container">
        <a href="{{ route('products') }}"><i class="fa-solid fa-angle-left"></i> {{ trans('language.go_back') }}</a>
    </div>
    <div class="container">
        <h1 class="page_title text-start">{{ trans('language.cart') }}</h1>
        <div class="cart__table pb-4">
            @if(session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> {{ session()->get('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session()->has('error'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> {{ session()->get('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(count($carts) > 0)
                @php
                    $total_price = 0;
                @endphp
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Product / Part Name</th>
                            <th>Type</th>
                            <th>Code</th>
                            <th>Unit</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($carts as $cart)
                            @if($cart['type'] == 'product')
                                @php
                                    $product = App\Models\Product::find($cart['product']['id']);
                                    $discount_amount = Helper::productDiscountAmount($product->id);
                                    $total_price = $total_price + ($cart['quantity'] * ($cart['product']['price'] - $discount_amount));
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $product->getTranslation(Session::get('language') ?? 'en', 'name') ?? $product->name ?? ''}}</td>
                                    <td>{{ $cart['type'] ?? ''}}</td>
                                    <td>{{ $product->code ?? ''}}</td>
                                    <td>
                                        <div class="cart__card__request--count">
                                            <span><a href="{{route('decrement.from.cart', $cart['product']['id'])}}" class="wishlist__card--sign"><i class="fa-solid fa-minus"></i></a></span>
                                            <span class="wishlist__card--number">{{ $cart['quantity'] ?? 0}}</span>
                                            <span><a href="{{route('increment.from.cart', $cart['product']['id'])}}" class="wishlist__card--sign"><i class="fa-solid fa-plus"></i></a></span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        ৳{{ Helper::priceAfterOffer($product->id) }}
                                        @if($product->discount)<del>৳{{ $product->price }}</del> @endif
                                    </td>
                                    <td class="text-center">৳{{ $cart['quantity'] * (Helper::priceAfterOffer($product->id)) }}</td>
                                    <td class="text-center">
                                        <a href="{{route('remove.from.cart', $cart['product']['id'])}}" class="text-danger" title="Remove from cart"><i class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>
                            @else
                                @php
                                    $part = App\Models\ProductPart::find($cart['product']['id']);
                                    $discount_amount = Helper::partDiscountAmount($part->id);
                                    $total_price = $total_price + ($cart['quantity'] * ($cart['product']['price'] - $discount_amount));
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $part->getTranslation(Session::get('language') ?? 'en', 'name') ?? $part->name ?? ''}}</td>
                                    <td>{{ $cart['type'] ?? ''}}</td>
                                    <td>{{ $part->code ?? ''}}</td>
                                    <td>
                                        <div class="cart__card__request--count">
                                            <span><a href="{{route('decrement.from.cart', $cart['product']['id'])}}" class="wishlist__card--sign"><i class="fa-solid fa-minus"></i></a></span>
                                            <span class="wishlist__card--number">{{ $cart['quantity'] ?? 0}}</span>
                                            <span><a href="{{route('increment.from.cart', $cart['product']['id'])}}" class="wishlist__card--sign"><i class="fa-solid fa-plus"></i></a></span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        ৳{{ Helper::partPriceFaterOffer($part->id) }}
                                        @if($part->discount)<del>৳{{ $part->price }}</del> @endif
                                    </td>
                                    <td class="text-center">৳{{ $cart['quantity'] * (Helper::partPriceFaterOffer($part->id)) }}</td>
                                    <td class="text-center">
                                        <a href="{{route('remove.from.cart', $cart['product']['id'])}}" class="text-danger" title="Remove from cart"><i class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endif
                        @empty
                        @endforelse

                    </tbody>
                </table>

                <div class="d-flex justify-content-end">
                    <h6 class="me-5">Subtotal: ৳{{$total_price}}</h6>
                    <hr>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('products')}}"  class="btn__secondary">{{ trans('language.btn_continue_shipping') }}</a>
                    @if (auth()->user())
                        @if (auth()->user()->role == 1)
                        <a href="{{ route('login') }}" class="btn__primary">Please Login! </a>
                        @else
                            <a href="{{ route('order') }}" class="btn__primary">{{ trans('language.btn_confirm_purchase') }}</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn__primary">{{ trans('language.btn_confirm_purchase') }}</a>
                    @endif
                </div>
            @else
                {{ Session::get('session_id') }}
                <p class="wish-alert">{{ trans('language.no_product_on_cart') }}!</p>
                <div class="col-lg-12 text-center wish-margin">
                    <a class="btn__secondary" href="{{route('products')}}">Choose Your Product</a>
                   </div>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('frontend.layout.app')
@section('content')
    <div id="products">
        <div class="breadcrumb__nk">
            <div class="container"><a href="{{ route('home') }}" class="text-light">{{ trans('language.home') }} / {{ $brand->title }} / {{ trans('language.products') }} </div>
        </div>
        
        <div class="container">
            <div class="row mt-5">
                @if ($products->count() > 0)
                    @foreach ($products as $product)
                        <div class="col-lg-3 mb-2">
                            <a href="{{ url('product/'.$product->slug) }}">
                                <div class="card product-cart">
                                    <img src="{{ asset('uploads/product-images/' . $product->thumbnail) }}" height="300px" alt="">
                                    <p title="{{$product->name}}" class="product-title text-uppercase">{{ Str::limit($product->getTranslation(Session::get('language') ?? 'en', 'title') ?? $product->name, 30, '...')  }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @else
                    <p>{{ trans('language.no_product_found') }}.</p>
                @endif
            </div>
        </div>
    </div>
    @push('footer')
        <script type="text/javascript">
            
        </script>
    @endpush
@endsection

@extends('front.master.master')
   @php
    $currency = getCurrentCurrency();
   @endphp
@section('title')
{{ $product->product_name }}
@endsection

@section('meta')
    <!-- Twitter Card  -->
    <meta name="keywords" content="{{ $product->product_keyword }}">
    <meta name="twitter:card" content="product">
    <meta name="twitter:title" content="{{ $product->product_name }}">
    <meta name="twitter:site" content="@shakhawat_fci">
    <meta name="twitter:creator" content="@shakhawat_fci">
    <meta name="twitter:description" content="@if($product->description) {{ $product->product_name }} @else {{ $seo_info->description }}  @endif">
    <meta name="twitter:image" content="{{ url('images/product/feature/'.$product->product_image) }}">


    <!-- Open Graph  -->
    <meta property="og:title" content="{{ $product->product_name }}" />
    <meta property="og:type" content="Product" />
    <meta property="og:url" content="{{ route('product.details',['id' => $product->id,'slug' => str_replace(' ', '-', $product->product_name)]) }}" />
    <meta property="og:image" content="{{ url('images/product/feature/'.$product->product_image) }}" />
    <meta property="og:description" content="@if($product->description) {{ $product->product_name }} @else {{ $seo_info->description }}  @endif" />
    @php
    $price = $product->selling_price;
    if($product->discount_status == 1 && $product->discount_amount > 0)
     {
       $price = $price - $product->discount_amount;
     }
    @endphp
    <meta property="og:price:amount" content="{{ $price }}" />
    <meta property="og:price:currency" content="{{ $currency->code }}"/>
@endsection
@push('style')
        <!--swiper css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/swiper.css') }}">
    <!--magnific css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/magnific.css') }}">
@endpush
@section('content')

        <div class="container mb30 mt30">
            <div class="row">
                <div class="col-lg-12 col-sm-12 ">
                    <div class="breadcrumb clearfix">
                        <div class="col-lg-6 col-sm-6">
                            <div class="float-l">
                                <a href="#"><span>Details</span></a>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6">
                            <div class="float-r">
                                <div class="breadcrumbs">
                                    <a href="#">Home <i class="lni lni-chevron-right"></i></a>
                                    <a href="{{ route('category.product',['id' => $product->category_id, 'slug' => str_replace(' ','-',$product->category->category_name)]) }}">{{ $product->category->category_name }}<i class="lni lni-chevron-right"></i></a>
                                    <a href="{{ route('subcategory.product',['id' => $product->sub_category_id, 'slug' => str_replace(' ','-',$product->sub_category->sub_category_name)]) }}">{{ $product->sub_category->sub_category_name }} </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

      <product-details :product='@json($product)' :currency='@json($currency)'></product-details>
@endsection


@push('script')
<script src="{{ asset('public/js/front.js') }}"></script>
@endpush

@extends('front.master.master')
@section('title')
 {{ $shop_info->shop_name }} | {{ $category->category_name }}
@endsection

@section('meta')
    <!-- Twitter Card  -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@my_twitter">
    <meta name="twitter:creator" content="@my_twitter">

    <!-- Open Graph  -->
    <meta property="og:title" content="{{ $shop_info->shop_name }} | {{ $category->category_name }}" />
    <meta property="og:type" content="Product" />
    <meta property="og:url" content="{{ route('category.product',['id' => $category->id,'slug' => str_replace(' ', '-', $category->category_name)]) }}" />
    <meta property="og:image" content="{{ url('images/setting/seo/'.$seo_info->meta_image) }}" />
    <meta property="og:description" content="Find all product  in {{ $category->category_name }} category" />
    <!-- <meta property="og:price:amount" content="$15.00" /> -->
@endsection
@section('content')

   <!-- home category from js  -->
    <category-subcategory :category='@json($category)'></category-subcategory>
   <!-- home category from js  -->

        <!--  end category-->

        <!--  start category product  -->
           <!-- @php
            $currency = getCurrentCurrency();
            @endphp -->
         <!-- <category-product :category_id="{{ $category->id }}" :category_name="{{ json_encode($category->category_name) }}" :currency="{{ $currency }}"></category-product> -->
        <!--  end   category product -->
@endsection

@push('script')
<script src="{{ asset('public/js/front.js') }}"></script>
@endpush
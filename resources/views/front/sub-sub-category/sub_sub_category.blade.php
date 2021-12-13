@extends('front.master.master')

@section('title')
 {{ $shop_info->shop_name }} | {{ $sub_sub_category->sub_sub_category_name }}
@endsection

@section('meta')
    <!-- Twitter Card  -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@my_twitter">
    <meta name="twitter:creator" content="@my_twitter">

    <!-- Open Graph  -->
    <meta property="og:title" content="{{ $shop_info->shop_name }} | {{ $sub_sub_category->sub_sub_category_name }}" />
    <meta property="og:type" content="Product" />
    <meta property="og:url" content="{{ route('subsubcategory.product',['id' => $sub_sub_category->id,'slug' => str_replace(' ', '-', $sub_sub_category->sub_sub_category_name)]) }}" />
    <meta property="og:image" content="{{ url('images/setting/seo/'.$seo_info->meta_image) }}" />
    <meta property="og:description" content="Find all product  in {{ $sub_sub_category->sub_sub_category_name }}" />
    <!-- <meta property="og:price:amount" content="$15.00" /> -->
@endsection

@section('content')
        <!--  start category product  -->
        @php 
            $currency = getCurrentCurrency();
            @endphp   

         <sub-sub-category-product  :sub_sub_category='@json($sub_sub_category)'
          :brands='@json($brands)'
         :currency="{{ $currency }}"></sub-sub-category-product>


        <!--  end   category product -->
@endsection

@push('script')
<script src="{{ asset('public/js/front.js') }}"></script>
@endpush
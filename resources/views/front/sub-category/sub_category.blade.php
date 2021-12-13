@extends('front.master.master')

@section('title')
 {{ $shop_info->shop_name }} | {{ $sub_category->sub_category_name }}
@endsection

@section('meta')
    <!-- Twitter Card  -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@my_twitter">
    <meta name="twitter:creator" content="@my_twitter">

    <!-- Open Graph  -->
    <meta property="og:title" content="{{ $shop_info->shop_name }} | {{ $sub_category->sub_category_name }}" />
    <meta property="og:type" content="Product" />
    <meta property="og:url" content="{{ route('subcategory.product',['id' => $sub_category->id,'slug' => str_replace(' ', '-', $sub_category->sub_category_name)]) }}" />
    <meta property="og:image" content="{{ url('images/setting/seo/'.$seo_info->meta_image) }}" />
    <meta property="og:description" content="Find all product  in {{ $sub_category->sub_category_name }}" />
    <!-- <meta property="og:price:amount" content="$15.00" /> -->
@endsection

@section('content')
      @if(count($sub_category->sub_sub_category) > 0)
        <!--  leve three category   -->
        <level-three-category :sub_category='@json($sub_category)'></level-three-category>
        <!--  level three category  -->
      @endif

        <!--  start category product  -->
        @php 
            $currency = getCurrentCurrency();
            @endphp   
         <sub-category-product  :sub_category='@json($sub_category)'
         :currency="{{ $currency }}"></sub-category-product>
        <!--  end   category product -->
@endsection

@push('script')
<script src="{{ asset('public/js/front.js') }}"></script>
@endpush
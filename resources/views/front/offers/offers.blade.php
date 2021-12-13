@extends('front.master.master')
@section('meta')
   <!-- Twitter Card  -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@my_twitter">
    <meta name="twitter:creator" content="@my_twitter">

    <!-- Open Graph  -->
    <meta property="og:title" content="{{ $seo_info->title }}" />
    <meta property="og:type" content="Ecommerce Site" />
    <meta property="og:url" content="{{ url('/') }}" />
    <meta property="og:image" content="{{ url('images/setting/seo/'.$seo_info->meta_image) }}" />
    <meta property="og:description" content="{{ $seo_info->description }}" />
@endsection
@section('content')

    <!--start banner-->
    <div class="container">
     	<all-offers></all-offers>
    </div>
    <!--end banner-->
@endsection

@push('script')
<script src="{{ asset('public/js/front.js') }}"></script>
@endpush
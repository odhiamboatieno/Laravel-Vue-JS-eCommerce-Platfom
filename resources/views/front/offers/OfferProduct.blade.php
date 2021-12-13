@extends('front.master.master')
@section('title')
{{ $campaign->title }}
@endsection
@section('meta')
   <!-- Twitter Card  -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@my_twitter">
    <meta name="twitter:creator" content="@my_twitter">

    <!-- Open Graph  -->
    <meta property="og:title" content="{{ $campaign->title }}" />
    <meta property="og:type" content="Ecommerce Site" />
    <meta property="og:url" content="{{  url('/').'offer/'.$campaign->id.'/'.str_replace('%','-percent',str_replace(' ', '-', $campaign->title)) }}" />
    <meta property="og:image" content="{{ url('images/campaign/meta/'.$campaign->meta_image) }}" />
    <meta property="og:description" content="{{ $campaign->campaign_title }}" />
@endsection
@section('content')
    <!--start banner-->
           @php 
            $currency = getCurrentCurrency();
            @endphp 
    <div class="container">
     	<offers-product :campaignid="{{ $campaign }}" :currency="{{ $currency }}"></offers-product>
    </div>
    <!--end banner-->
@endsection

@push('script')
<script src="{{ asset('public/js/front.js') }}"></script>
@endpush
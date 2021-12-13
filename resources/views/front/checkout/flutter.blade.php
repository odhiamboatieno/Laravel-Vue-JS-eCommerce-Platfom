@extends('front.master.master')

@section('title')
 {{ $shop_info->shop_name }} | Checkout
@endsection

@section('meta')
    <!-- Twitter Card  -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@my_twitter">
    <meta name="twitter:creator" content="@my_twitter">

    <!-- Open Graph  -->
    <meta property="og:title" content=" {{ $shop_info->shop_name }} | Checkout" />
    <meta property="og:type" content="Ecommerce Site" />
    <meta property="og:url" content="{{ route('checkout.get') }}" />
    <meta property="og:image" content="{{ url('images/setting/seo/'.$seo_info->meta_image) }}" />
    <meta property="og:description" content="{{ $seo_info->description }}" />
@endsection

@section('content')

<div class="container">
  <flutter-wave-payment  :order="{{ $order }}" :currency="{{ $currency }}"
   :shop_info="{{ $shop_info }}" :payment_info="{{ $payment_info }}"></flutter-wave-payment>
</div>
@endsection
@push('script')
<script src="https://checkout.flutterwave.com/v3.js"></script>
<script src="{{ asset('public/js/front.js') }}"></script>
@endpush

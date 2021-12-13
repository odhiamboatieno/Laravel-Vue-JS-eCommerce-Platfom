@extends('front.master.master')

@section('title')
  {{ $data->title }} | {{ $shop_info->shop_name }}
@endsection

@section('meta')
    <!-- Twitter Card  -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@my_twitter">
    <meta name="twitter:creator" content="@my_twitter">

    <!-- Open Graph  -->
    <meta property="og:title" content="{{ $data->title }}" />
    <meta property="og:type" content="Ecommerce Site" />
    <meta property="og:url" content="{{ url('/') }}" />
    <!-- <meta property="og:price:amount" content="$15.00" /> -->
@endsection
@section('content')

<div class="container">
<div class="row">
    <div class="wrapper wrapper-content animated fadeInRight article">
        <div class="row justify-content-md-center">
            <div class="col-lg-10">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="text-center" style="margin: 30px 0 40px 0px;">
                            <h1>
                                {{ $data->title }}
                            </h1>
                        </div>
                        <p style="text-align: justify;">
                            {!! $data->description !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </div>
</div>

@endsection
@push('script')
<script src="{{ asset('public/js/front.js') }}"></script>
@endpush

@extends('front.master.master')

@section('title')
 {{ $shop_info->shop_name }} | Order History
@endsection

@section('meta')
    <!-- Twitter Card  -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@my_twitter">
    <meta name="twitter:creator" content="@my_twitter">

    <!-- Open Graph  -->
    <meta property="og:title" content="{{ $seo_info->title }} | Orders " />
    <meta property="og:type" content="Ecommerce Site" />
    <meta property="og:url" content="{{ url('/') }}" />
    <meta property="og:image" content="{{ url('images/setting/seo/'.$seo_info->meta_image) }}" />
    <meta property="og:description" content="{{ $seo_info->description }}" />

@endsection
@section('content')
<section class="user-profile mt30 mb30">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-sm-12">
                @include('front.user.user-menu')
            </div>
            <div class="col-lg-9 col-sm-12">
                <div class="deshboard ">

                    <div class="row">
                        <div class="col-lg-12 col-sm-12 ">
                            <div class="breadcrumb clearfix">
                                <div class="col-lg-6 col-sm-6">
                                    <div class="float-l">
                                        <a href="#"><span>Order History</span></a>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    <div class="float-r">
                                        <div class="breadcrumbs">
                                            <a href="#">Home <i class="lni lni-chevron-right"></i></a>
                                            <a href="#">Order History</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @php 
            $currency = getCurrentCurrency();
            @endphp 
            <order-history :currency="{{ $currency }}"></order-history>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('script')
<script src="{{ asset('public/js/front.js') }}"></script>
@endpush
@extends('front.master.master')

@section('title')
{{ $shop_info->shop_name }} | Coupon
@endsection

@section('meta')
    <!-- Twitter Card  -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@my_twitter">
    <meta name="twitter:creator" content="@my_twitter">

    <!-- Open Graph  -->
    <meta property="og:title" content="{{ $seo_info->title }}" />
    <meta property="og:type" content="Ecommerce Site" />
    <meta property="og:url" content="{{ url('register') }}" />
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
                                        <a href="#"><span>My Coupon</span></a>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    <div class="float-r">
                                        <div class="breadcrumbs">
                                            <a href="{{ route('user.profile') }}">Home <i class="lni lni-chevron-right"></i></a>
                                            <a href="#">Coupon</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                    <div class="col-lg-12 col-sm-12">
                        @if(count($coupon) > 0)
                        @php 
                        $currency = getCurrentCurrency();
                        @endphp 
                        <div class="form row bg-white ">
                            @foreach($coupon as $value)
                          <div class="col-md-5 mx-auto bg-shadow">
                              <div  style="padding: 10px;">
                              <input type="text" value="{{ $value->coupon_code }}" class="form-control"  readonly>
                               <p style="margin-top:10px;">Apply <span class="theme-color">{{ $value->coupon_code }}</span> during checkout to Get 
                               {{ $value->amount }} 
                               @if($value->amount_type == 1)
                               <span>{{ $currency->symbol }}</span>
                               @else
                               <span>% Up To {{  $value->max_amount_limit }} {{ $currency->symbol }}</span>
                               @endif
                               Discount
                              </p>
                              </div>
                          </div>
                          @endforeach
                        </div>

                        @else
                        <div class="form bg-white text-center" style="color:gray;margin-top:30px;">
                             <h3>No coupons available 🥺  , please shop with us to get coupons</h3>
                        </div>
                        @endif
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('script')
<script src="{{ asset('public/js/front.js') }}"></script>
@endpush

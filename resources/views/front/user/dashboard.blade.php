@extends('front.master.master')

@section('title')
 {{ $shop_info->shop_name }} | Profile
@endsection

@section('meta')
    <!-- Twitter Card  -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@my_twitter">
    <meta name="twitter:creator" content="@my_twitter">

    <!-- Open Graph  -->
    <meta property="og:title" content="{{ $seo_info->title }} | Profile" />
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
            <!--end user profile-->

            <div class="col-lg-9 col-sm-12 ">
                <div class="deshboard">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 ">
                            <div class="breadcrumb clearfix">
                                <div class="col-lg-6 col-sm-6">
                                    <div class="float-l">
                                        <a href="#"><span>Deshboard</span></a>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    <div class="float-r">
                                        <div class="breadcrumbs">
                                            <a href="#">Home <i class="lni lni-chevron-right"></i></a>
                                            <a href="{{ route('user.profile') }}">Deshboard</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  end breadcrumn-->
                    @php 
            $currency = getCurrentCurrency();
            @endphp 
               <user-dashboard :currency="{{ $currency }}"></user-dashboard>

                    <div class="row">
                        <div class="col-lg-12 col-sm-12">
                            <div class="user-info table-responsive mt15">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col" colspan="2">User Information</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td scope="row">Name:</td>
                                            <td>{{ Auth::user()->name }}</td>
                                        </tr>
                                        <tr>
                                            <td scope="row">Email:</td>
                                            <td>{{ Auth::user()->email }}</td>
                                        </tr>
                                        <tr>
                                            <td scope="row">Phone:</td>
                                            <td>{{ Auth::user()->phone }}</td>
                                        </tr>
                                        <tr>
                                            <td scope="row">Address:</td>
                                            <td>{{ Auth::user()->address }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a href="{{ route('user.information') }}" class="button button-sm bg-dark2 color-white "> Edit</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!--end user profile-->
        </div>
    </div>

</section>
@endsection

@push('script')
<script src="{{ asset('public/js/front.js') }}"></script>
@endpush
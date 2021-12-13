@extends('front.master.master')

@section('title')
{{ $shop_info->shop_name }} | Change Password
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
                                        <a href="#"><span>Change Password</span></a>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    <div class="float-r">
                                        <div class="breadcrumbs">
                                            <a href="#">Home <i class="lni lni-chevron-right"></i></a>
                                            <a href="#">Password Change</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <div class="form bg-white bg-shadow">
                            <!-- <div class="heading text-center clearfix">
                                <h4 class="pt10 color-dark login-headline">Change Password</h4>
                                <small class="heading heading-solid center-block heading-width-100 border-light"></small>
                            </div> -->

                            <div class="p30">
                                <form method="post" action="{{ route('change.password.post') }}">
                                    @csrf
                                    <div class="form-group">
                                        <!-- <label for="oldpassword">Old Password: <span class="text-danger">*</span></label> -->
                                        <input id="password" type="password" 
                                        class="sign-up-input form-control @error('password') is-invalid @enderror"
                                         name="oldpassword" required 
                                         placeholder="Old Password">
                                        @error('oldpassword')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <p class="ptsan-regular"></p>
                                    </div>
                                    <div class="form-group">
                                        <!-- <label for="password">New Password: <span class="text-danger">*</span></label> -->
                                        <input 
                                        class="sign-up-input form-control @error('password') is-invalid @enderror"
                                         name="password" 
                                         type="password"
                                          id="password"
                                           required autocomplete="new-password"
                                           placeholder="New Password"
                                           >
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <p class="ptsan-regular"></p>
                                    </div>
                                    <div class="form-group">
                                        <!-- <label for="password2">Confirm Password: <span class="text-danger">*</span></label> -->
                                        <input id="password-confirm" type="password"
                                         class="sign-up-input form-control"
                                          name="password_confirmation"
                                          placeholder="Confirm Password" required autocomplete="new-password">
                                        <p class="ptsan-regular"></p>
                                    </div>
                                    <button type="submit" class="button button-md bg-dark2 color-white mb20 theme-background" style="width: 100%">Update</button>
                                </form>
                            </div>
                        </div>
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

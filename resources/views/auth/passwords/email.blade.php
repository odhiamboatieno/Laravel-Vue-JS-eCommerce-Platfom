@extends('front.master.master')

@section('title')
 {{ $shop_info->shop_name }} | Password Reset
@endsection

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
<section class="forget-password">
    <div class="bg-overlay pt50 pb50">
        <div class="container">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <div class="form bg-white bg-shadow">
                            <div class="heading text-center clearfix">
                                <h4 class="pt10 color-dark login-headline">Reset Password</h4>
                                <small class="heading heading-solid center-block heading-width-100 border-light"></small>
                            </div>

                            <div class="p30">
                                <div class="forget-pass pb30 text-center">
                                  
                                    <p>Provide that email you used in sign up</p>
                                </div>
                                @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif
                                <form method="POST" action="{{ route('user-password-email-reset-link') }}">
                                    {{ csrf_field() }}
                                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                       
                                        <input class="sign-up-input form-control" name="email" placeholder="Email" type="email" id="email" value="{{ old('email') }}" required>
                                        <p class="ptsan-regular"></p>
                                    @if ($errors->has('email'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                    </div>

                                    <button type="submit"
                                     class="button button-md bg-dark2 color-white mb20" 
                                     style="width: 100%">Submit & check your  email inbox</button>
                                </form>
                                <div class="account-info clearfix width-res">
                                    <h5 class="color-dark">
                                        <a class="float-l te-und-ho" href="{{ route('login') }}">Login</a>
                                        <a href="{{ route('register') }}" class=" float-r te-und-ho">Create Account</a>
                                    </h5>
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
@extends('front.master.master')

@section('title')
 {{ $shop_info->shop_name }} | Login
@endsection

@section('meta')
    <!-- Twitter Card  -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@my_twitter">
    <meta name="twitter:creator" content="@my_twitter">

    <!-- Open Graph  -->
    <meta property="og:title" content="{{ $seo_info->title }}" />
    <meta property="og:type" content="Ecommerce Site" />
    <meta property="og:url" content="{{ url('login') }}" />
    <meta property="og:image" content="{{ url('images/setting/seo/'.$seo_info->meta_image) }}" />
    <meta property="og:description" content="{{ $seo_info->description }}" />

@endsection
@section('content')
<section class="signin-form">
    <div class="bg-overlay pt50 pb50">
        <div class="container">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <div class="form bg-white bg-shadow">
                            <div class="heading text-center clearfix">
                                <h4 class="pt10 theme-color login-headline">Sign in</h4>
                                <small class="heading heading-solid center-block heading-width-100 border-light"></small>
                            </div>
                            <div class="p30">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group">

                                        <input value=""
                                        class="sign-up-input form-control @error('email') is-invalid @enderror"
                                         name="email" value="{{ old('email') }}"
                                          required autocomplete="email" placeholder="Email" autofocus>
                                        <p class="ptsan-regular text-danger">
                                            @error('email')

                                            {{ $message }}

                                            @enderror
                                        </p>
                                    </div>
                                    <div class="form-group ">

                                        <input id="password" type="password"
                                        class="sign-up-input form-control @error('password') is-invalid @enderror"
                                         name="password" required
                                         placeholder="Password"
                                         autocomplete="current-password">
                                        <p class="ptsan-regular text-danger">
                                            @error('password')

                                            {{ $message }}

                                            @enderror
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <input class="starterPrice my-radio" type="checkbox"
                                        name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}><label for="remember">{{ __('Remember Me') }}</label>
                                    </div>

                                    <button type="submit" class="button button-md bg-dark2 color-white mb20 theme-background" style="width: 100%">{{ __('Login') }}</button>
                                </form>
                                <div class="account-info text-center clearfix width-res">
                                    <h5 class="color-dark">
                                        <a class="float-l te-und-ho" href="{{ route('password.request') }}">Forget Password?</a>
                                        <a href="{{ url('register') }}" class="float-r te-und-ho">Create Account</a>
                                    </h5>
                                </div>
                                <div class="register-social text-center mt20">
                                <p class="login-with-social" >Login with Social Media</p>
                                @foreach($social_provider as $value)
                                    <a href="{{ url('login/'.$value->provider) }}"
                                     class="button ml-social button-sm
                                     @if($value->provider == 'facebook') bg-fb @endif
                                     @if($value->provider == 'google') bg-plus @endif
                                     @if($value->provider == 'twitter') bg-info @endif
                                      color-white mb10">
                                     <i class="lni lni-{{ $value->provider }}" aria-hidden="true"></i>
                                     </a>
                                 @endforeach

                                </div>

                        <!-- <div class="p-30 text-center">
                                <p class="login-with-social" >or just login with phone number</p>
                                <form action="{{ route('send.otp') }}" method="POST">
                                    @csrf
                                <div class="form-group">

                                        <input
                                        class="form-checkout sign-up-input form-control @error('phone') is-invalid @enderror"
                                         name="phone" value="{{ old('phone') }}"
                                          required autocomplete="phone" placeholder="Phone Number: ex-017********" autofocus max="11">
                                        <p class="ptsan-regular text-danger">
                                            @error('phone')

                                            {{ $message }}

                                            @enderror
                                        </p>
                                </div>

                                    <button type="submit" class="button button-md bg-dark2 color-white mb20 theme-background"
                                     style="width: 100%">Sign Up / Sign in</button>
                                </form>
                                </div> -->

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

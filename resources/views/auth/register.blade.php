@extends('front.master.master')

@section('title')
{{ $shop_info->shop_name }} | Signup
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
<section class="signup-form">
    <div class="bg-overlay pt50 pb50">
        <div class="container">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <div class="form bg-white bg-shadow">
                            <div class="heading text-center clearfix">
                                <h4 class="pt10 color-dark login-headline">Sign Up</h4>
                                <small class="heading heading-solid center-block heading-width-100 border-light"></small>
                            </div>

                            <div class="p30">
                                <form method="post" action="{{ route('register') }}" id="#">
                                    @csrf
                                    <div class="form-group ">
                                        
                                        <input 
                                        class="sign-up-input form-control @error('name') is-invalid @enderror"
                                         name="name"
                                          value="{{ old('name') }}" 
                                          required autocomplete="name"
                                          placeholder="Name"
                                           autofocus type="text" id="name">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <p class="ptsan-regular"></p>
                                    </div>
                                    <div class="form-group">
                                        <input class="sign-up-input form-control @error('email') is-invalid @enderror"
                                         name="email"
                                          type="text"
                                           id="email"
                                           placeholder="Email"
                                            value="{{ old('email') }}" required autocomplete="email">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <p class="ptsan-regular"></p>
                                    </div>
                                    <div class="form-group">
                                        <input class="sign-up-input form-control @error('phone') is-invalid @enderror"
                                         name="phone"
                                          type="text"
                                           id="phone"
                                           placeholder="Your Phone Number"
                                            value="{{ old('phone') }}" autocomplete="phone">
                                    </div>
 
                                    <div class="form-group">
                                        <input 
                                        class="sign-up-input form-control @error('password') is-invalid @enderror" 
                                        name="password" type="password"
                                        placeholder="Password"
                                         id="password" required autocomplete="new-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <p class="ptsan-regular"></p>
                                    </div>

                                    <div class="form-group">
                            
                                        <input id="password-confirm"
                                         type="password" 
                                         class="sign-up-input form-control"
                                         placeholder="Confirm Password"
                                          name="password_confirmation" required autocomplete="new-password">
                                        <p class="ptsan-regular"></p>
                                    </div>

                                    <button type="submit" 
                                    class="button button-md bg-dark2 color-white theme-background" 
                                    style="width: 100%">Sign Up</button>
                                </form>
                                <div class="mt20 clearfix  text-center">
                                    <h5 class="mt20">Already have a account? <strong>
                                            <a href="{{ route('login') }}" class="te-und-ho">Login</a>
                                        </strong></h5>
                                </div>
                                <div class="register-social text-center mt20">
                                <p class="ml-social login-with-social" >Login with Social Media</p>
                                        @foreach($social_provider as $value)
                                            <a href="{{ url('login/'.$value->provider) }}"
                                             class="button button-sm 
                                             @if($value->provider == 'facebook') bg-fb @endif
                                             @if($value->provider == 'google') bg-plus @endif
                                             @if($value->provider == 'twitter') bg-info @endif
                                              color-white mb10">
                                             <i class="lni lni-{{ $value->provider }}" aria-hidden="true"></i> 
                                             </a>
                                @endforeach  
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

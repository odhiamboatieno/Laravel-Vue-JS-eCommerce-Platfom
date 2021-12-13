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
<section class="signin-form">
    <div class="bg-overlay pt50 pb50">
        <div class="container">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <div class="form bg-white bg-shadow">
                            <div class="heading text-center clearfix">
                                <div class="pt10 color-dark login-headline">{{ __('Reset Password') }}</div>
                                <small class="heading heading-solid center-block heading-width-100 border-light"></small>
                            </div>
                            <div class="p30">
                                @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session()->get('status') }}
                                    </div>
                                @endif
                                <form method="POST" action="{{ route('password.update') }}">
                                    @csrf
                                    <div class="form-group">
                                        
                                        <input id="email" type="hidden" class="form-control" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus readonly="true">
                                    </div>
                                   
                                    <div class="form-group">
                                        <input class="sign-up-input form-control @error('password') is-invalid @enderror" 
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
                          
                                    <button type="submit" class="button button-md bg-dark2 color-white mb20 theme-background" style="width: 100%">{{ __('Reset Password') }}</button>
                                </form>
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
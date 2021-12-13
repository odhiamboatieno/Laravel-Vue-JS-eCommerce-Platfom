@extends('front.master.master')

@section('title')
 {{ $shop_info->shop_name }} | Submit OTP
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
                                <h4 class="pt10 theme-color login-headline">Confirm OTP</h4>
                            </div>
                            <div class="p30">
                                <form method="POST" action="{{ route('submit.otp') }}">
                                    @csrf
                                    <div class="form-group">
                                        <input type="hidden" value="{{ $phone }}" name="phone">
                                        <input 
                                        class="form-checkout sign-up-input form-control @error('code') is-invalid @enderror"
                                         name="code" value="{{ old('code') }}"
                                          required autocomplete="code" placeholder="Provide OTP Code" autofocus>
                                        <p class="ptsan-regular text-danger">
                                            @error('code')

                                            {{ $message }}

                                            @enderror
                                        </p>
                                    </div>

                                    <button type="submit" class="button button-md bg-dark2 color-white mb20 theme-background"
                                     style="width: 100%">Submit & Login</button>


                                </form>
                                <form method="POST" action="{{ route('send.otp') }}" >
                                	@csrf
                                    <input type="hidden" name="phone" value="{{ $phone }}">
                                	<button type="submit" class="btn btn-link text-center">Din't get code ? Click here to Resend Code</button>
                                	
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

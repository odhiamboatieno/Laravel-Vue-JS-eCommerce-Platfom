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
                                <form class="m-t" role="form" method="POST" action="{{ route('user.confirm.password') }}">
                                    @csrf
                                    <div class="form-group">
                                        <input type="password" class="form-control"  name="password" placeholder="Enter Password" value="{{ old('password') }}" required="">
                                        @if ($errors->has('password'))
                                        <span class="text-danger"><strong>{{ $errors->first('password') }}</strong></span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control"  name="password_confirmation" placeholder="Confirm Password" value="{{ old('password') }}" required="">
                                    </div>
                                   <input type="hidden" name="identity" value="{{ $token }}">
                                    <button type="submit"
                                     class="button button-md bg-dark2 color-white mb20" 
                                     style="width: 100%">Confirm Password</button>
                                </form>
                                @if(session()->has('error'))
                                    <h2 class="text-danger text-center">{!! session()->get('error') !!}</h2>
                                @endif
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
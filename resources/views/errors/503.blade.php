@extends('front.master.master')

@section('title')
 {{ $shop_info->shop_name }} | Blocked
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
                        <div class="text-center">
                            <img src="{{ asset('images/static/rolling-eyes.png') }}" class="img-fluid" height="10%" width="10%">
                            <h1 style="margin-top: 1%; font-size: 4.8em">406</h1>
                            <h3 class="font-bold">Site is temporarily blocked by the author due to multiple use of single licence,
                                please contact at <a href="mailto:limmexbd@gmail.com" style="color: dodgerblue;">limmexbd@gmail.com.</a></h3>
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

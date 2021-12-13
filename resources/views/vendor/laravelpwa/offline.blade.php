@extends('front.master.master')

@section('title')
 {{ $shop_info->shop_name }} | Network Error!
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
                            <h1 style="margin-top: 1%; font-size: 4.8em">error!</h1>
                            <h3 class="font-bold">!Oops look like there is network error check your internet connection and reload the apps.</h3>
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

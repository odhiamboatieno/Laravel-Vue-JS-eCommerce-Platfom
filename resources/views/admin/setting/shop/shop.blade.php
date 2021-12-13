@extends('admin.master')
@section('title','Shop Setting')
@section('content')

           <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-4">
                    <h2>@yield('title')</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/admin') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>@yield('title')</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-sm-8">
                    <div class="title-action">
                        <a data-toggle="modal" class="btn btn-outline-secondary" href="#pwaModal">Click here For PWA App Setting</a>
                    </div>
                </div>
            </div>




            <div class="wrapper wrapper-content white-bg">
               <shop-setting></shop-setting>
               <pwa-setting></pwa-setting>
            </div>

@endsection

@push('script')
 <script src="{{ asset('public/js/shop_setting.js') }}"></script>
@endpush

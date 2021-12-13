@extends('admin.master')
@section('title','Date Time Slot Setting')
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
                <a data-toggle="modal" class="btn btn-primary" href="#CreateTimeSlot">Create New Time Slot</a>
            </div>
        </div>
    </div>

    <create-time-slot></create-time-slot>

    <div class="wrapper wrapper-content">
    <div class="row">
    <div class="col-md-5 mr-auto ml-auto border p-4 bg-white">
    <date-slot-setting></date-slot-setting>
    </div>

     <div class="col-md-7 mr-auto ml-auto border p-4 bg-white">
     <view-time-slot></view-time-slot>
    </div>
    </div>

    </div>

@endsection

@push('script')
 <script src="{{ asset('public/js/shop_setting.js') }}"></script>

@endpush

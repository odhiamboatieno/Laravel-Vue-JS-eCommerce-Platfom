@extends('admin.master')
@section('title','Change Password')
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
                
            </div>
        </div>
    </div>

    <div class="row wrapper wrapper-content">
        <div class="col-md-4 offset-md-4 border p-4">
            @if(Session::has('message'))
                <div class="alert alert-{{Session::get('status')}}" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span>{{ Session::get('message') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    
                </div>
            @endif
            <h3>Change Password</h3>
             
            <form class="m-t" role="form"  method="POST" action="{{ route('admin.changepass.submit') }}">
                @csrf
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Current Password" required="" name="current_password" value="{{old('current_password')}}">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="New Password" required="" name="password" value="{{old('password')}}">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Confirm Password" required="" name="password_confirmation">
                </div>   
                <button type="submit" class="btn btn-primary block full-width m-b">Save</button>
            </form>
        </div>
    </div>

@endsection
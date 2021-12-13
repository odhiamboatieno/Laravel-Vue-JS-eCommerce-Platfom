<!DOCTYPE html>
<html>
<head>
    <title>Admin Confirm Password</title>
    @include('admin.include.header_asset')
</head>
<body style="background-color: #ececec;">

<div class="row">
     <div class="col-md-4 mr-auto ml-auto">
                <div class="middle-box text-center loginscreen animated fadeInDown" style="padding-top: 0 !important">
        <div>
            <div>
     <a href="{{ url('/') }}"><img class="img-fluid" style="margin: 20px 0px;" src="{{ asset('images/logo/'.$shop_info->logo_footer)}}"></a>
            </div>
            <h3>Welcome to {{ $shop_info->shop_name }}</h3>

            <p>Login as admin to control over.</p>

            <form class="m-t" role="form" method="POST" action="{{ route('admin.confirm.password') }}">
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
                <button type="submit" class="btn btn-primary block full-width m-b">Confirm Password</button>
            </form>

            @if(session()->has('error'))
            <h2 class="text-danger text-center">{!! session()->get('error') !!}</h2>
            @endif
  
        </div>
    </div>
     </div>
 </div>
    @include('admin.include.footer_asset')

</body>
</html>
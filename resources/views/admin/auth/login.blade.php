<!DOCTYPE html>
<html>
<head>
	<title>Admin Login</title>
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
            <h3>Login as admin</h3>

            <form class="m-t" role="form"  method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                <div class="form-group">
                    <input type="email" class="form-control"  name="email" placeholder="Email Address" value="{{ old('email') }}" required="">
                    @if ($errors->has('email'))
                    <span class="text-danger"><strong>{{ $errors->first('email') }}</strong></span>
                    @endif
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" required="" name="password">
                </div>   

<!--                 <div class="form-group">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} id="rememberme" class="filled-in chk-col-pink">
                        <label for="rememberme">Remember Me</label>
                </div> -->
                <button type="submit" class="btn btn-primary block full-width m-b">Login</button>

                <a href="{{ route('password-change.request') }}">Forgot password?</a>
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
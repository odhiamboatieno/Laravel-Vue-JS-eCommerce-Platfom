<!DOCTYPE html>
<html>
<head>
    <title>Admin Reset Password</title>
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
            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ session()->get('resent') }}
                </div>
            @endif
            <form class="m-t" role="form"  method="POST" action="{{ route('admin.chkmail.password') }}">
                @csrf
                <div class="form-group">
                    <input type="email" class="form-control"  name="email" placeholder="Email Address" value="{{ old('email') }}" required="">
                    @if ($errors->has('email'))
                    <span class="text-danger"><strong>{{ $errors->first('email') }}</strong></span>
                    @endif
                </div>
               
                <button type="submit" class="btn btn-primary block full-width m-b">Send Password Reset Link</button>
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
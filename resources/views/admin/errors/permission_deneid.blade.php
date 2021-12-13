<!DOCTYPE html>
<html>
<head>
	<title>Access Deneid</title>
	@include('admin.include.header_asset')
</head>

<body class="gray-bg">


     <div class="middle-box text-center animated fadeInDown">
        <h1>403</h1>
        <h3 class="font-bold">Access Deneid</h3>

        <div class="error-desc">
            Opps! you don't have the permission to visit the page<br/>
            You can go back to dashboard: <br/><a href="{{ url('admin') }}" class="btn btn-primary m-t">Dashboard</a>
        </div>
    </div>
	


	@include('admin.include.footer_asset')

</body>


</html>
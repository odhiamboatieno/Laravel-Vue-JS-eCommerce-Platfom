<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provide Purchase Code</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo/'.$shop_info->favicon) }}" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
<!--line icons-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/fonts/LineIcons.min.css') }}">
<!--style css-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
<!--setting css-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/setting.css') }}">
<!--responsive css-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">
 <!--responsive css-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }}">
<!--google font css-->
<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<section class="signin-form " id="front-wrapper">
    <div class="bg-overlay pt50 pb50">
        <div class="container">
            <div class="container">
                <div class="row">
                 <verification ></verification>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
        var base_url = "{{ url('/') }}"+'/';
    </script>
<script src="{{ asset('public/js/front.js') }}"></script>
</body>
</html>

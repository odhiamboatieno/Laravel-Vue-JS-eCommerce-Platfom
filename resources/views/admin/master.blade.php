<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('images/logo/'.$shop_info->favicon) }}" />
    <!-- <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet'> -->
    <title>@yield('title','Limmerz')</title>

    @include('admin.include.header_asset')

    <style >
    	/* body {

    		 font-family: 'Raleway' !important;

    	} */
.swal2-container.swal2-center {

    z-index: 1060;
}

   .theme-background
   {
      background: {{ $shop_info->theme_color }} !important;
   }
   .theme-color
   {
     color : {{ $shop_info->theme_color }} !important;
   }

   .theme-hover-bg:hover {

    background-color: {{ $shop_info->theme_color }} !important;

   }

   .theme-hover-color:hover {
    color : {{ $shop_info->theme_color }} !important;
   }

   /*vue js date picker style */
   .v2-date-wrap
   {
    min-height: 42px !important;
    width: 100% !important;
   }

    </style>




</head>

<body class="" onload="loaded()">

    <div id="wrapper">
       @include('admin.include.sidebar')
        <div id="page-wrapper" class="gray-bg">
          <div class="row border-bottom">
            @include('admin.include.topbar')
          </div>

        <!-- all dynamic content will go there  -->
        @yield('content')

            <div class="footer">
                
            </div>
        </div>
    </div>

<!-- look the file what it include  -->
@include('admin.include.footer_asset')
<!-- look the file what it include  -->

<script type="text/javascript">
       function loaded(){
         var segment3 = '{{ Request::segment(1) }}';
         var current_url = base_url + segment3;
         // alert(current_url);
         $('.active_url').parents('.parent').addClass('active');
         $('.active_url').parents('.nav-second-level').siblings('a').prop('aria-expanded',true);
         $('.active_url').parents('.nav-second-level').css('display','block');

         $('.tooltip-demo').tooltip({
            selector: "[data-toggle=tooltip]",
            container: "body"
        });
   }
    </script>

</body>

</html>

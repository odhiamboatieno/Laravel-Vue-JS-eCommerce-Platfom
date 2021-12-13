    <!-- Mainly scripts -->
    <script>
        var base_url = "{{ url('/') }}"+'/';
    </script>
    <script src="{{ asset('admin-assets/js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/bootstrap.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

    <!-- Custom and plugin javascript -->
    <script src="{{ asset('admin-assets/js/inspinia.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/pace/pace.min.js') }}"></script>
    <!-- basic form  -->
    <script src="{{ asset('admin-assets/js/plugins/iCheck/icheck.min.js') }}"></script>
        <script>
            $(document).ready(function () {
                $('.i-checks').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                });
            });
        </script>

    <!-- basic form    -->


    <!-- Jasny -->
    <script src="{{ asset('admin-assets/js/plugins/jasny/jasny-bootstrap.min.js')}}"></script>
 
   <!-- every page will have separate vue script and it will be pushed dynamically

   for more infromation check laravel docs   -->

   <!-- select 2 -->
       <!-- Select2 -->
    <script src="{{ asset('admin-assets/js/plugins/select2/select2.full.min.js') }}"></script>
   <!-- select 2 -->
    @stack('script')

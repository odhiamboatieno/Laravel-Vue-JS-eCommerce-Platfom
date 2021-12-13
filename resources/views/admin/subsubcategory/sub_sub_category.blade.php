@extends('admin.master')
@section('title','Sub Sub Category')
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
                        <a data-toggle="modal" class="btn btn-primary" href="#modal-form">Create New</a>
                    </div>


                </div>
            </div>

            <create-subcategory :categories="{{ $category }}" :brands="{{ $brand }}"></create-subcategory>

 

            <div class="wrapper wrapper-content">
               <view-subcategory :categories="{{ $category }}" :brands="{{ $brand }}"></view-subcategory>
            </div>

@endsection

@push('script')
<script type="text/javascript">
    $(document).ready(function () {

        $(".select2_demo_2").select2();

    })
</script>
 <script src="{{ asset('public/js/sub_sub_category.js') }}"></script>
@endpush

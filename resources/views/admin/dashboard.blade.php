@extends('admin.master')
@section('title','Dashboard')
@section('content')

<dashboard-summary></dashboard-summary>
<chart-summary></chart-summary>

@endsection
 
@push('script')

<script src="{{ asset('public/js/dashboard.js') }}"></script>

@endpush
@extends('layouts.master')

@section('css')
    <!--Chartist Chart CSS -->
    <link rel="stylesheet" href="{{ URL::asset('plugins/chartist/css/chartist.min.css') }}">
@endsection

@section('breadcrumb')
    <div class="col-sm-6 text-left">
        <h4 class="page-title">Dashboard</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Welcome to Attendance Management System</li>
        </ol>
    </div>
@endsection
@php
    $role = Auth::user()->role->role_id ?? '';
@endphp
@section('content')
    @if ($role == 1)
        {{-- dashboard eployee --}}
        @include('Dashboard.employee')
    @else
        {{--  dashboard admin --}}
        @include('Dashboard.admin')
    @endif
@endsection

@section('script')
    <!--Chartist Chart-->
    <script src="{{ URL::asset('plugins/chartist/js/chartist.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/chartist/js/chartist-plugin-tooltip.min.js') }}"></script>
    <!-- peity JS -->
    <script src="{{ URL::asset('plugins/peity-chart/jquery.peity.min.js') }}"></script>
    <script src="{{ URL::asset('assets/pages/dashboard.js') }}"></script>
@endsection

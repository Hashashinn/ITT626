<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Attendance Management System</title>
    <meta content="Admin Dashboard" name="description" />
    <meta content="Themesbrand" name="author" />
    @include('layouts.head')
</head>
<style>
    /* Add CSS styles to your stylesheet */
    .loading-container {
        display: flex;
        justify-content: center;
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8);
        z-index: 9999;
    }

    .loader {
        border: 4px solid #f3f3f3;
        /* Light gray border */
        border-top: 4px solid #3498db;
        /* Blue border for animation */
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 2s linear infinite;
        /* Animation name and duration */
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<body>
    <div class="loading-container">
        <div class="loader"></div>
    </div>
    <div id="wrapper">
        @include('layouts.header')
        @php
            $role_id = Auth::user()->role->role_id ?? '';
        @endphp
        {{-- condition if employeee get side emp if admin get sidebar admin --}}
        @if ($role_id == 1)
            {{-- sidebar eployee --}}
            @include('layouts.sidebar.employee')
        @elseif ($role_id == 3)
            {{-- sidebar admin manager --}}
            @include('layouts.sidebar.adminManager')
        @elseif($role_id == 4)
            {{-- sidebar admin branch --}}
            @include('layouts.sidebar.adminBranch')
        @else
            {{--  sidebar admin --}}
            @include('layouts.sidebar.admin')
        @endif

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    {{-- @include('layouts.settings') --}}
                    @yield('content')
                </div>
            </div>
        </div>
        @include('layouts.footer')
        @include('layouts.footer-script')
    </div>
    @include('includes.flash')
</body>
<script>
    $(window).on('load', function() {
        $('.loading-container').fadeOut('slow');
    });
</script>

</html>

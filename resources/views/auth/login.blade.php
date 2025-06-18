@extends('layouts.master-blank')

@section('content')
    <div class="wrapper-page">
        <div class="card overflow-hidden account-card mx-3" id="loginJs">
            <div class="bg-primary p-4 text-white text-center position-relative">
                <h4 class="font-20 m-b-5">Welcome Back !</h4>
                <p class="text-white-50 mb-4">Sign in AMS</p>
                <a href="{{ route('welcome') }}" class="logo logo-admin">
                    <h1>A</h1>
                </a>
            </div>
            <div class="account-card-content">
                <form class="form-horizontal m-t-30" id="loginForm">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="col-form-label ">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password" class="col-form-label ">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required autocomplete="current-password">
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <label class="col-form-label ">
                                <a href="/requestResetPassword">
                                    {{ __('Forgot Password?') }}
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-6 text-right">
                            <button class="btn btn-primary w-md waves-effect waves-light" id="loginBtn">Log
                                In</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end wrapper-page -->
@endsection

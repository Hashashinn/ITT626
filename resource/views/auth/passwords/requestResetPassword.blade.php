@extends('layouts.master-blank')

@section('content')
    <div class="wrapper-page">
        <div class="card overflow-hidden account-card mx-3" id="resetPasswordFormJs">
            <div class="bg-primary p-4 text-white text-center position-relative">
                <h4 class="font-20 m-b-5">Attendance Management System</h4>
                <p class="text-white-50 mb-4">Reset Password AMS</p>
                <a href="/" class="logo logo-admin">
                    <h1>P</h1>
                </a>
            </div>
            <div class="account-card-content">
                <form class="form-horizontal m-t-30" id="resetPasswordForm">
                    @csrf
                    <div class="form-group">
                        <label for="email" class="col-form-label">{{ __('E-Mail Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    </div>

                    <div class="form-group row mb-0 justify-content-center">
                        <div class="col-md-6">
                            <button class="btn btn-primary" id="resetBtn">{{ __('Send Password Reset Link') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

{{-- <div class="form-group row align-items-center justify-content-center" style="height: 100vh;">
    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
    <div class="col-md-6">
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
            value="{{ old('email') }}" required autocomplete="email" autofocus>
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

<div class="form-group row mb-0 justify-content-center">
    <div class="col-md-6">
        <button class="btn btn-primary" id="resetBtn">{{ __('Send Password Reset Link') }}</button>
    </div>
</div> --}}

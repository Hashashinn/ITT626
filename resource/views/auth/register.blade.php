@extends('layouts.master-blank')

@section('content')
    <div class="wrapper-page" id="registerJs">
        <div class="card overflow-hidden account-card mx-3">
            <div class="bg-primary p-4 text-white text-center position-relative">
                <h4 class="font-20 m-b-5">Hi New Comers !</h4>
                <p class="text-white-50 mb-4">Register to AMS</p>
                <a href="{{ route('welcome') }}" class="logo logo-admin">
                    <h1>A</h1>
                </a>
            </div>
            <div class="account-card-content">
                <form class="form-horizontal m-t-30" id="registerForm">
                    @csrf`

                    <div class="form-group">
                        <label for="email" class="col-form-label ">{{ __('Email Address') }}</label>

                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="user[email]" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-form-label ">{{ __('Full Name') }}</label>

                        <input id="name" type="tetx" class="form-control " name="user[name]" required autofocus>

                    </div>

                    <div class="form-group">
                        <label for="password" class="col-form-label ">{{ __('Password') }}</label>


                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="user[password]" required autocomplete="current-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="col-form-label ">{{ __('Branch') }}</label>
                        <input type="hidden" name="role[role_id]" value="4">
                        <select name="user[location_id]" id="" class="form-control">
                            @php
                                $locations = getAllLocation();
                            @endphp
                            <option value="">Please Select</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                            @endforeach
                        </select>
                    </div>
                

                <div class="form-group row m-t-20">
                    <div class="col-sm-6 text-right">
                        <a href="/login"class="btn btn-default w-md waves-effect waves-light">Back</a>
                    </div>
                    <div class="col-sm-6 text-">
                        <button class="btn btn-primary w-md waves-effect waves-light" id="saveBtn">
                            Submit </button>
                    </div>
                </div>
            </form>

        </div>


    </div>
        <!-- end wrapper-page -->
    @endsection

    {{-- @section('script')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#registerForm').validate({
                    rules: {
                        'user[email]': {
                            required: true,
                            email: true
                        },
                        'user[name]': {
                            required: true
                        },
                        'user[password]': {
                            required: true,
                            minlength: 6
                        }
                    },
                    messages: {
                        'user[email]': {
                            required: 'Please enter an email address.',
                            email: 'Please enter a valid email address.'
                        },
                        'user[name]': {
                            required: 'Please enter your full name.'
                        },
                        'user[password]': {
                            required: 'Please enter a password.',
                            minlength: 'Your password must be at least 6 characters long.'
                        }
                    },
                    submitHandler: function(form) {
                        // Handle the form submission here
                        form.submit();
                    }
                });
            });
        </script>
    @endsection --}}

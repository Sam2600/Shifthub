@extends('layouts.master')

@section('content')
    <div
        class="rounded mt-5 bg-white d-flex flex-column flex-md-row flex-lg-row justify-content-evenly align-items-center p-5">

        <div class="d-flex flex-column justify-content-center col-12 col-md-6 col-lg-6">

            {{-- If login error occur, show this error message --}}
            @if (Session::has('login-error'))
                <div class="alert alert-danger text-center alert-dismissible col-md-10 offset-md-1 fade show" role="alert">
                    {{ Session::get('login-error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- if user try to go other route without login --}}
            @if (Session::has('auth'))
                <div class="alert alert-warning text-center alert-dismissible col-md-10 offset-md-1 fade show" role="alert">
                    {{ Session::get('auth') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <h1 class="text-dark col-md-10 offset-md-1 my-1 mb-4">Admin Register</h1>

            <form method="POST" class="d-flex flex-column gap-3" action="{{ route('admins.register') }}">

                @csrf

                {{-- Name --}}
                <div class="col-md-10 offset-md-1 d-flex flex-column gap-1">
                    <label for="name" class="form-label">Name</label>
                    <div class="input-group">
                        <input type="text"
                            class=" shadow form-control @if ($errors->has('name')) is-invalid @endif" id="name"
                            name="name" value="{{ old('name') }}">
                    </div>
                    @error('name')
                        <div id="nameHelp" class="form-text text-danger">{{ $errors->first('name') }}</div>
                    @enderror

                </div>

                {{-- Email --}}
                <div class="col-md-10 offset-md-1 d-flex flex-column gap-1">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <input type="text"
                            class="shadow form-control @if ($errors->has('email')) is-invalid @endif" id="email"
                            name="email" value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <div id="emailHelp" class="form-text text-danger">{{ $errors->first('email') }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="col-md-10 offset-md-1 d-flex flex-column gap-1">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password"
                            class="shadow form-control @if ($errors->has('password')) is-invalid @endif" id="password"
                            name="password" value="{{ old('password') }}">
                        <button type="button" id="toggle-password" class="btn shadow btn-dark">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div id="passwordHelp" class="form-text text-danger">{{ $errors->first('password') }}</div>
                    @enderror
                </div>

                {{-- Confirm password --}}
                <div class="col-md-10 offset-md-1 d-flex flex-column gap-1">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <input type="password"
                            class="shadow form-control @if ($errors->has('password_confirmation')) is-invalid @endif"
                            id="password_confirmation" name="password_confirmation"
                            value="{{ old('password_confirmation') }}">
                        <button type="button" id="toggle-password" class="btn shadow btn-dark">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <div id="password_confirmationHelp" class="form-text text-danger">
                            {{ $errors->first('password_confirmation') }}</div>
                    @enderror
                </div>

                <button type="submit" name="submit" class="btn col-md-10 offset-md-1 mt-4 shadow active login"
                    role="button" aria-pressed="true">
                    Register
                </button>
            </form>
        </div>

        <div class="col-12 col-md-6 col-lg-5">
            <img src="{{ URL::asset('images/register.jpg') }}" class="w-100 px-xl-5" alt="Login Image">
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var togglePassword = document.getElementById('toggle-password');
        var passwordField = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
            } else {
                passwordField.type = 'password';
            }
        });
    });
</script>

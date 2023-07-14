@extends('layouts.master')

@section('title', 'Admin Login')

@section('content')
    <br>
    <div class="shadow p-3 col-md-10 offset-md-1 bg-white rounded my-5" style="background: #e8ebe8">


        <div class="row login-container">

            <div class="col-md-6 my-5">

                {{-- If login error occur, show this error message --}}
                @if (Session::has('login-error'))
                    <div class="alert alert-danger alert-dismissible col-md-10 offset-md-1 fade show" role="alert">
                        {{ Session::get('login-error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- if user try to go other route without login --}}
                @if (Session::has('auth'))
                    <div class="alert alert-warning alert-dismissible col-md-10 offset-md-1 fade show" role="alert">
                        {{ Session::get('auth') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <h1 class="text-dark col-md-10 offset-md-1 my-1 mb-4">Admin Login</h1>

                <form method="POST" action="{{ route('admins.login') }}">

                    @csrf

                    {{-- admin's id --}}
                    <div class="col-md-10 offset-md-1 mb-3">
                        <label for="employee_id" class="form-label">Admin Id</label>
                        <div class="input-group">
                            <input type="text"
                                class=" shadow form-control @if ($errors->has('id')) is-invalid @endif"
                                id="employee_id" name="id" value="{{ old('id') }}">
                        </div>
                        @error('id')
                            <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('id') }}</div>
                        @enderror

                    </div>

                    {{-- admin's password --}}
                    <div class="col-md-10 offset-md-1 mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password"
                                class="shadow form-control @if ($errors->has('password')) is-invalid @endif"
                                id="password" name="password" value="{{ old('password') }}">
                            <button type="button" id="toggle-password" class="btn shadow btn-dark"><i
                                    class="far fa-eye"></i></button>
                        </div>
                        @error('password')
                            <div id="passwordHelp" class="form-text text-danger">{{ $errors->first('password') }}</div>
                        @enderror
                    </div>
                    <div>
                        <button type="submit" name="submit"
                            class="btn col-md-10 offset-md-1 my-3 mb-4 shadow active login" role="button"
                            aria-pressed="true">Login</button>
                    </div>
                </form>

            </div>

            <div class="col-md-6 my-5">
                <div class="image-container"></div>
            </div>
        </div>

    </div>


    <style>
        .image-container {
            background-image: url('{{ Storage::url('signin-image.jpg') }}');
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            width: 100%;
            height: 100%;
        }
    </style>
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

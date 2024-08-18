@extends('layouts.master')

@section('content')
    <div class="container bg-white rounded shadow my-3 pt-1">
        <code class="py-1">
            <pre>
                Hint ***
                During for only early access,
                For admin with create access => ID - 1, Password - admin123123
                For admin with update access => ID - 2, Password - admin456456</pre>
        </code>
    </div>
    <div
        class="rounded bg-white d-flex flex-column flex-md-row flex-lg-row justify-content-center align-items-center h-auto p-5">

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
                <div class="alert alert-warning text-center alert-dismissible col-md-10 offset-md-1 fade show"
                    role="alert">
                    {{ Session::get('auth') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <h1 class="text-dark col-md-10 offset-md-1 my-1 mb-4">Admin Login</h1>

            <form method="POST" class="d-flex flex-column gap-3" action="{{ route('admins.login') }}">

                @csrf

                {{-- admin's id --}}
                <div class="col-md-10 offset-md-1 d-flex flex-column gap-1">
                    <label for="employee_id" class="form-label">Admin ID</label>
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
                <div class="col-md-10 offset-md-1 d-flex flex-column gap-1">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password"
                            class="shadow form-control @if ($errors->has('password')) is-invalid @endif" id="password"
                            name="password" value="{{ old('password') }}">
                        <button type="button" id="toggle-password" class="btn shadow btn-dark"><i id="eyeIcon"
                                class="material-icons">visibility</i></button>
                    </div>
                    @error('password')
                        <div id="passwordHelp" class="form-text text-danger">{{ $errors->first('password') }}</div>
                    @enderror
                </div>

                {{-- <p class="col-md-10 offset-md-1">
                    Already registered ? Please login <a href="/admins/register" class="underline">Here</a> Test
                </p> --}}

                <button type="submit" name="submit" class="btn col-md-10 offset-md-1 shadow active login mt-3"
                    role="button" aria-pressed="true">
                    Login
                </button>

            </form>

        </div>

        <div class="col-12 col-md-6 col-lg-5">
            <img src="{{ URL::asset('images/loginImage.jpg') }}" class="w-100 px-xl-5" alt="Login Image">
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {

        var togglePassword = document.getElementById('toggle-password');
        var passwordField = document.getElementById('password');
        
        var eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function() {
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.innerText  = 'visibility_off';
            } else {
                passwordField.type = 'password';
                eyeIcon.innerText  = 'visibility';
            }
        });
    });
</script>

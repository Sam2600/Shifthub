<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- bootstrap.css --}}
    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap.css') }}">
    {{-- material icons --}}
    <link rel="stylesheet" href="{{ URL::asset('css/material-icon.css') }}">
    {{-- fav icon --}}
    <link rel="icon" href="{{ URL::asset('images/shifthub.png') }}" type="image/png">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ URL::asset('css/font-awsome.css') }}">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="{{ URL::asset('css/google-font.css') }}">
    {{-- My Custom Css --}}
    <link rel="stylesheet" href="{{ URL::asset('css/shifthub.css') }}">
    {{-- laravel ajax --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SiftHub</title>

</head>

<body>

    @if (Session::has('id'))
        @include('layouts.nav')
    @endif


    <div class="container my-4">
        @yield('content')
    </div>

    <script src="{{ URL::asset('js/popper.js') }}"></script>
    <script src="{{ URL::asset('js/bootstrap.js') }}"></script>
    <script src="{{ URL::asset('js/jquery.js') }}"></script>

    @yield('script')

</body>

</html>

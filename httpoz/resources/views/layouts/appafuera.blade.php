<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sb-admin.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/santa_rosa.css') }}" rel="stylesheet">

    @yield('head')

</head>
<body>
            @yield('content')
            <script src="{{ asset('js/jquery.min.js') }}"></script>
            <script src="{{ asset('js/bootstrap/bootstrap.bundle.min.js') }}"></script>
            <script src="{{ asset('js/jquery-easing/jquery.easing.min.js') }}"></script>
            <script src="{{ asset('js/sb-admin.min.js') }}"></script>
</body>
</html>

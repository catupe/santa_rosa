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


</head>
<body class="fixed-nav bg-dark sidenav-toggled" id="page-top">
    <div id="wrapper">

        @section('sidebar')
        @show

        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h4 class="page-header santa_rosa-page-header">@yield('title')</h4>

                        @if( isset($code_error) and ($code_error == 1) )
                            @include('mensajes.error', array('mensaje'=>$mensaje))
                        @elseif ( isset($code_error) and ($code_error == 2) )
                            @include('mensajes.info', array('mensaje'=>$mensaje))
                        @endif

                        @yield('content')
                    </div>
                </div>
            </div>
            <!--
            <footer class="sticky-footer">
              <div class="container">
                <div class="text-center">
                  <small>Copyright © Your Website 2017</small>
                </div>
              </div>
            </footer>
            -->

            <script src="{{ asset('js/jquery.min.js') }}"></script>
            <script src="{{ asset('js/bootstrap/bootstrap.bundle.min.js') }}"></script>
            <script src="{{ asset('js/jquery-easing/jquery.easing.min.js') }}"></script>
            <script src="{{ asset('js/sb-admin.min.js') }}"></script>
            <script src="{{ asset('js/santa_rosa.js') }}"></script>
        </div>
    </div>
</body>
</html>

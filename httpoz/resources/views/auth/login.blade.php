@extends('layouts.appafuera')

@section('head')
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
@endsection

@section('content')
    <section class="hero">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-xs-12">
                        <div class="card border-none">
                            <div class="card-block">

								<div class="mt-2">
                                    <!--<img src="img/logo.png" alt="Male" class="brand-logo mx-auto d-block img-fluid rounded-circle"/>-->
                                </div>

                                <p class="mt-4 text-white lead">
                                    Ingresar al sistema
                                </p>
                                <div class="mt-4">
                                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                                        {{ csrf_field() }}
                                        <!--
                                        <div class="form-group">
                                            <input type="email" class="form-control" id="email" value="" placeholder="Usuario">
                                        </div>
                                        -->
                                        <div class="form-group">
                                            <label class="sr-only" for="email">E-Mail Address</label>
                                            <div class="input-group">
                                                <div class="input-group-addon" style="width: 2.6rem"><i class="fa fa-at"></i></div>
                                                <input type="text" name="email" class="form-control is-invalid" id="email" placeholder="you@example.com" required="" autofocus="">
                                                <div class="invalid-feedback">
                                                    Please provide a valid city.
                                                </div>
                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <!--
                                        <div class="form-group">
                                            <input type="password" class="form-control" id="password" value="" placeholder="Contrase&ntilde;a">
                                        </div>
                                        -->
                                        <div class="form-group">
                                            <label class="sr-only" for="email">E-Mail Address</label>
                                            <div class="input-group">
                                                <div class="input-group-addon" style="width: 2.6rem"><i class="fa fa-key"></i></div>
                                                <input type="password" name="password" class="form-control" id="password" placeholder="Password" required="">
                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <label class="custom-control custom-checkbox mt-2">
                                            <input type="checkbox" class="custom-control-input" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description text-white">Mantenerme logeado</span>
                                        </label>
                                        <button type="submit" class="btn btn-primary float-right">Aceptar</button>
                                    </form>
                                    <div class="clearfix"></div>
                                    <p class="content-divider center mt-4"></p>
                                </div>
								<!--
                                <p class="mt-4 social-login text-center">
                                    <a href="#" class="btn btn-twitter"><em class="ion-social-twitter"></em></a>
                                    <a href="#" class="btn btn-facebook"><em class="ion-social-facebook"></em></a>
                                    <a href="#" class="btn btn-linkedin"><em class="ion-social-linkedin"></em></a>
                                    <a href="#" class="btn btn-google"><em class="ion-social-googleplus"></em></a>
                                    <a href="#" class="btn btn-github"><em class="ion-social-github"></em></a>
                                </p>
                                <p class="text-center">
                                    Don't have an account yet? <a href="register.html">Sign Up Now</a>
                                </p>
								-->
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
					<!--
                    <div class="col-sm-12 mt-5 footer">
                        <p class="text-white small text-center">
                            &copy; 2017 Login/Register Forms. A FREE Bootstrap 4 component by
                            <a href="https://wireddots.com">Wired Dots</a>. Designed by <a href="https://twitter.com/attacomsian">@attacomsian</a>
                        </p>
                    </div>
					-->
                </div>
            </div>
        </section>
@endsection

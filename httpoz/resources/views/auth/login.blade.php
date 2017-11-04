@extends('layouts.appafuera')

@section('head')
    <link href="{{ asset('css/santa_rosa_login.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container vertical-offset-100">
        <div class="row justify-content-center">
            <div class="col-sm-8 col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <div class="text-center">
                            <img src="http://www.molinosantarosa.com.uy/imgs/layout/logo.png" class="img-fluid" alt="Conxole Admin"/>
                        </div>
                    </div>
                    <div class="card-body">
                      <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div class="row">
                          <div class="col-12 mb-3">
                            <label for="usuario">Usuario</label>
                            <input type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="usuario" placeholder="Usuario" value="" required>
                            @if ($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                          </div>

                        </div>
                        <div class="row">
                          <div class="col-12 mb-3">
                            <label for="password">Contrase&ntilde;a</label>
                            <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" id="password" placeholder="Contrase&ntilde;a" required>
                            @if ($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif
                          </div>
                        </div>

                        <button class="btn btn-primary" type="submit">Aceptar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
      </div>

@endsection

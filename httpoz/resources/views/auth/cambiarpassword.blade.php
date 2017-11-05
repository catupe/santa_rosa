@extends('layouts.appadentro')

@section('sidebar')
    @include('sidebar.ppal')
@endsection

@section('content')
    @section('title', 'Cambiar Contrase&ntilde;a')
    <div class="col-12 col-sm-12 col-md-8">
      <form id="cambiar_password" class="form-horizontal" method="POST">
        {{ csrf_field() }}
        <div class="form-group row">
          <label for="pass_vieja" class="col-4 col-sm-4 col-form-label">Contrase&ntilde;a Actual</label>
          <div class="col-sm-8 col-8">
            <input type="password" class="form-control" id="pass_vieja" name="pass_vieja" placeholder="Contrase&ntilde;a Actual">
          </div>
        </div>
        <div class="form-group row">
          <label for="pass_nueva" class="col-4 col-sm-4 col-form-label">Contrase&ntilde;a Nueva</label>
          <div class="col-8">
            <input type="password" class="form-control" id="pass_nueva" name="pass_nueva" placeholder="Contrase&ntilde;a Nueva">
          </div>
        </div>
        <div class="form-group row">
          <label for="pass_nueva_2" class="col-4 col-sm-4 col-form-label">Repita la Contrase&ntilde;a Nueva</label>
          <div class="col-8 col-sm-8">
            <input type="password" class="form-control" id="pass_nueva_2" name="pass_nueva_2" placeholder="Repita la Contrase&ntilde;a Nueva">
          </div>
        </div>
        <div class="form-group row">
          <div class="col-4 col-sm-4">
            <button type="submit" name="aceptar" class="btn btn-dark">Aceptar</button>
          </div>
        </div>
      </form>
    </div>
@endsection

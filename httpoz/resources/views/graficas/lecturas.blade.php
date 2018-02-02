@extends('layouts.appadentro')

@section('head')
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
@endsection

@section('sidebar')
    @include('sidebar.ppal')
@endsection

@section('content')
    @section('title', 'Ver Lecturas Balanzas')
    <div class="col-12 col-sm-12 col-md-8">
      <form id="balanzas-verlecturas" class="form-horizontal" method="POST">
        {{ csrf_field() }}
        <div class="form-group row">
          <label for="balanza" class="col-4 col-sm-4 col-form-label">Seleccione Balanza</label>
          <div class="col-sm-6 col-8">
            <select class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" id="balanza" name="balanza">
                <option>Seleccione...</option>
                @foreach($balanzas as $balanza)
                    @if( $balanza->id == $balanza_actual )
                      @php ($activa = 'selected')
                    @else
                        @php ($activa = '')
                    @endif
                    <option id="{{ $balanza->id }}" value="{{ $balanza->id }}" {{ $activa }}>{{ $balanza->nombre_mostrar }}</option>
                @endforeach
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label for="fecha_ini" class="col-4 col-sm-4 col-form-label">Fecha Inicio</label>
          <div class="input-group col-6 col-md-4">
            <input type="text" class="form-control form_datetime date_input" value="2012-05-15 21:05" id="fecha_ini" name="fecha_ini">
            <span class="input-group-addon date"><i class=" fa fa-calendar" aria-hidden="true"></i></span>
          </div>
        </div>
        <div class="form-group row">
          <label for="fecha_fin" class="col-4 col-sm-4 col-form-label">Fecha Fin</label>
          <div class="input-group col-6 col-md-4">
            <input type="text" class="form-control  form_datetime date_input" value="2017-12-15 21:05" id="fecha_fin" name="fecha_fin">
            <span class="input-group-addon date"><i class="fa fa-calendar" aria-hidden="true"></i></span>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-4 col-sm-4">
            <button type="button" name="aceptar" id="aceptar" class="btn btn-dark">Aceptar</button>
          </div>
        </div>
      </form>
    </div>
    <div id="curve_chart"></div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.min.js') }}" charset="UTF-8"></script>
    <script type="text/javascript" src="{{ asset('js/locales/bootstrap-datetimepicker.es.js') }}" charset="UTF-8"></script>
    <script type="text/javascript" src="{{ asset('js/charts/charts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/gral.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/graficas.js') }}"></script>
@endsection

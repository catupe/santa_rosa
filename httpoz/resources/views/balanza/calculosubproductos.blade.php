@extends('layouts.appadentro')

@section('head')
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
@endsection

@section('sidebar')
    @include('sidebar.ppal')
@endsection

@section('content')
    @section('title', 'C&aacute;lculo Subproductos')
    <div class="col-12 col-sm-12 col-md-8">
      <form id="balanzas-verlecturas" class="form-horizontal" method="POST">
        {{ csrf_field() }}
        <div class="form-group row">
          <label for="fecha_ini" class="col-4 col-sm-4 col-form-label">Fecha Inicio</label>
          <div class="input-group col-6 col-md-4">
            <input type="text" class="form-control form_datetime date_input" value="{{ $fecha_ini_actual }}" id="fecha_ini" name="fecha_ini">
            <span class="input-group-addon date"><i class=" fa fa-calendar" aria-hidden="true"></i></span>
          </div>
        </div>
        <div class="form-group row">
          <label for="hora_fin" class="col-4 col-sm-4 col-form-label">Hora Fin</label>
          <div class="input-group col-6 col-md-4">
            <select class="form-control custom-select" id="hora_fin" name="hora_fin">
              <option id="30" value="30" {{ $hora_fin_actual == '30' ? 'selected' : '' }} >30 minutos</option>
              <option id="1" value="60"  {{ $hora_fin_actual == '60' ? 'selected' : '' }}>1 hora</option>
              <option id="1" value="1440"  {{ $hora_fin_actual == '1440' ? 'selected' : '' }}>24 horas</option>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-4 col-sm-4">
            <button type="submit" name="aceptar" class="btn btn-dark">Aceptar</button>
          </div>
        </div>
      </form>
    </div>
    @if( count($lecturas) > 0 )
      <div class="col-12 col-sm-12 col-md-12">
        <table class="table table-sm table-striped table-responsive-sm">
          <thead>
            <tr>
              <!--<th scope="col">#</th>-->
              <th scope="col">Balanza</th>
              <th scope="col">Lectura</th>
              <th scope="col">Lectura Acumulada</th>
              <th scope="col">Lectura Cantidad</th>
              <th scope="col">Fecha</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($lecturas as $key1 => $lectura1)
              @foreach ($lectura1 as $key => $lectura)
                <tr data-toggle="tooltip" data-placement="top" title="{{ $lectura->comentarios }}">
                    <!--<td>{{ $lectura->id }}</td>-->
                    <td>{{ $lectura1->nombre_balanza }}</td>
                    <td>{{ $lectura->lectura }}</td>
                    <td>{{ $lectura->lectura_acumulada }}</td>
                    <td>{{ $lectura->lectura_cantidad }}</td>
                    <td>{{ $lectura->created_at }}</td>
                    <!--<td>{{ $lectura->comentarios }}</td>-->
                </tr>
              @endforeach
            @endforeach
          </tbody>
        </table

      </div>
    @endif
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.min.js') }}" charset="UTF-8"></script>
    <script type="text/javascript" src="{{ asset('js/locales/bootstrap-datetimepicker.es.js') }}" charset="UTF-8"></script>
    <script type="text/javascript" src="{{ asset('js/balanza.verlecturas.js') }}"></script>
@endsection

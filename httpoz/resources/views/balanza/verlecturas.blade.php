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
            <button type="submit" name="aceptar" class="btn btn-dark">Aceptar</button>
          </div>
        </div>
      </form>
    </div>
    @if( count($lecturas) > 0 )
      <div class="col-12 col-sm-12 col-md-12">
        <table class="table table-striped table-responsive-sm">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Lectura</th>
              <th scope="col">Lectura Acumulada</th>
              <th scope="col">Lectura Cantidad</th>
              <th scope="col">Fecha</th>
              <th scope="col">Comentarios</th>
              <th scope="col">
                <button type="button" class="btn btn-outline-dark btn-sm" data-toggle="modal" data-target="#agregarLectura" data-modo="1">Agregar Lectura</button>
              </th>
            </tr>
          </thead>
          <tbody>
            @foreach ($lecturas as $key => $lectura)
              <tr>
                  <td>{{ $lectura->id }}</td>
                  <td>{{ $lectura->lectura }}</td>
                  <td>{{ $lectura->lectura_acumulada }}</td>
                  <td>{{ $lectura->lectura_cantidad }}</td>
                  <td>{{ $lectura->created_at }}</td>
                  <td>{{ $lectura->comentarios }}</td>
                  <td><button type="button" class="btn btn-outline-dark btn-sm" data-toggle="modal" data-target="#agregarLectura"
                        data-modo="2"
                        data-lectura="{{ $lectura->lectura }}"
                        data-lectura-acumulada="{{ $lectura->lectura_acumulada }}"
                        data-fecha="{{ $lectura->created_at }}"
                        data-comentarios="{{ $lectura->comentarios }}"
                        data-id="{{ $lectura->id }}">Editar</button></td>
              </tr>
            @endforeach
          </tbody>
        </table

        {{ $lecturas->links('paginado.ppal') }}

      </div>
      <div class="modal fade" id="agregarLectura" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Agregar Lectura</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form>
                <div class="form-group">
                  <label for="balanza-nueva" class="col-form-label">Balanza:</label>
                  <select class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" id="balanza-nueva" name="balanza-nueva" required>
                      <option value="">Seleccione...</option>
                      @foreach($balanzas as $balanza)
                          @if( $balanza->id == $balanza_actual )
                            @php ($activa = 'selected')
                          @else
                              @php ($activa = '')
                          @endif
                          <option id="{{ $balanza->id }}" value="{{ $balanza->id }}" {{ $activa }}>{{ $balanza->nombre_mostrar }}</option>
                      @endforeach
                  </select>
                  <div class="invalid-feedback" id="invalid-balanza-nueva"></div>
                </div>
                <div class="form-group">
                  <label for="lectura-nueva" class="col-form-label">Lectura:</label>
                  <input type="text" class="form-control" id="lectura-nueva" name="lectura-nueva" required>
                  <div class="invalid-feedback" id="invalid-lectura-nueva"></div>
                </div>
                <div class="form-group">
                  <label for="lectura-acumulada-nueva" class="col-form-label">Lectura Acumulada:</label>
                  <input type="text" class="form-control" id="lectura-acumulada-nueva" name="lectura-acumulada-nueva" required>
                  <div class="invalid-feedback" id="invalid-lectura-acumulada-nueva"></div>
                </div>
                <div class="form-group">
                  <label for="fecha-nueva" class="col-form-label">Fecha Lecura</label>
                  <div class="input-group">
                    <input type="text" class="form-control form_datetime date_input" id="fecha-nueva" name="fecha-nueva" required>
                    <span class="input-group-addon date"><i class=" fa fa-calendar" aria-hidden="true"></i></span>
                  </div>
                  <div class="invalid-feedback" id="invalid-fecha-nueva"></div>
                </div>
                <div class="form-group">
                  <label for="comentarios-nueva" class="col-form-label">Comentarios:</label>
                  <textarea class="form-control" id="comentarios-nueva" name="comentarios-nueva"></textarea>
                </div>
              </form>
            </div>
            <input type="hidden" id="modo-nueva" name="modo-nueva">
            <input type="hidden" id="id-nueva" name="id-nueva">
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              <button type="button" class="btn btn-dark ladda-button" data-style="zoom-in" id="guardar">Guardar</button>
            </div>
          </div>
        </div>
      </div>
    @endif
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.min.js') }}" charset="UTF-8"></script>
    <script type="text/javascript" src="{{ asset('js/locales/bootstrap-datetimepicker.es.js') }}" charset="UTF-8"></script>
    <script type="text/javascript" src="{{ asset('js/balanza.verlecturas.js') }}"></script>
@endsection

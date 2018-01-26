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

        <div class="control-group controls">
          <div class="controls" id="subproductos_form">
            @if( count($valores_subproductos) == 0 )
              <div class="entry row">
                 <div class="col-6 col-md-4">
                   <select class="form-control custom-select id_sp" id="id_sp" name="id_sp[]">
                      @foreach($subproductos as $k => $v)
                        <option id="{{ $v->id }}" value="{{ $v->id }}">{{ $v->nombre_mostrar }}</option>
                      @endforeach
                    </select>
                 </div>
                 <div class="col-6 col-md-4 input-group">
                   <input type="number" name="valores_sp[]" class="form-control" id="valores_sp" placeholder="Peso">
                   <div class="input-group-append btn-add">
                     <span class="input-group-text btn btn-outline-secondary"><i class="fas fa-plus"></i></span>
                   </div>
                 </div>
               </div>
               <br>
             @endif
             @foreach ($valores_subproductos as $kvsp => $vvsp)
               <div class="entry row">
                  <div class="col-6 col-md-4">
                    <select class="form-control custom-select id_sp" id="id_sp" name="id_sp[]">
                       @foreach($subproductos as $k => $v)
                         <option id="{{ $v->id }}" value="{{ $v->id }}" {{ $kvsp == $v->id ? 'selected' : '' }} >{{ $v->nombre_mostrar }}</option>
                       @endforeach
                     </select>
                  </div>
                  <div class="col-6 col-md-4 input-group">
                    <input type="number" name="valores_sp[]" class="form-control" id="valores_sp" placeholder="Peso" value="{{ $vvsp }}">
                    <div class="input-group-append btn-add">
                      <span class="input-group-text btn btn-outline-secondary"><i class="fas fa-plus"></i></span>
                    </div>
                  </div>
                </div>
                <br>
             @endforeach

           </div>
        </div>
        <!--
        <div class="form-group row">
          <label for="afrechillo" class="col-4 col-sm-4 col-form-label">Afrechillo</label>
          <div class="input-group col-6 col-md-4">
            <input type="number" class="form-control" value="{{ $afrechillo }}" id="afrechillo" name="afrechillo">
          </div>
        </div>
        <div class="form-group row">
          <label for="semolin" class="col-4 col-sm-4 col-form-label">Semolin</label>
          <div class="input-group col-6 col-md-4">
            <input type="number" class="form-control" value="{{ $semolin }}" id="semolin" name="semolin">
          </div>
        </div>
        -->
        <div class="form-group row">
          <div class="col-4 col-sm-4">
            <button type="submit" name="aceptar" class="btn btn-dark">Aceptar</button>
          </div>
        </div>
      </form>
    </div>


    @if( count($lecturas) > 0)
      <hr>
      <div class="col-12">
          <div class="card-deck">
            <div class="card bg-light border-dark mb-3">
              <div class="card-header border-dark"><h6>Lecturas</h6></div>
              <div class="card-body">
                @foreach ($calculo_cantidades_lecturas["cantidades"] as $k => $v)
                  <p class="card-text">Lecturas correspondientes a la <strong>{{ $v["nombre"] }}</strong> - <strong>{{ $v["total"] }}</strong></p>
                @endforeach
                <p class="card-text">Total de lecturas <strong>{{ $calculo_cantidades_lecturas["total"] }}</strong></p>
                <!--
                @foreach ($lecturas as $key1 => $lectura1)
                  <p class="card-text"><strong>{{ $lectura1->nombre_balanza }}</strong></p>
                  <p class="card-text">
                  @foreach ($lectura1 as $key => $lectura)
                    | {{ $lectura->lectura_acumulada }} |
                  @endforeach
                  </p>
                @endforeach
                -->
                <!--<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>-->
              </div>
            </div>
            <div class="card bg-light border-dark mb-3">
              <div class="card-header border-dark"><h6>Pesajes</h6></div>
              <div class="card-body">
                <p class="card-text">Trigo - <strong>100 &#37;</strong> ( <strong>{{ $calculo_trigo }} Kg.</strong> )</p>
                @foreach ($balanzas as $k => $v)
                  @if($v->es_trigo == 0)
                    <p class="card-text">{{ $v->nombre }} - <strong>{{ str_limit($calculo_ptjes_harina[$v->id], 6) }} &#37;</strong> ( <strong>{{ $calculo_pesos_balanzas[$v->id] }} Kg.</strong> )</p>
                  @endif
                @endforeach
              </div>
            </div>
            <div class="card bg-light border-dark mb-3">
              <div class="card-header border-dark"><h6>Subproductos</h6></div>
              <div class="card-body">
                <p class="card-text">Subtotal <strong>{{ str_limit($calculo_subtotal, 8) }} Kg.</strong></p>
                <p class="card-text">Subproducto <strong>{{ str_limit($calculo_subproducto,6) }} &#37;</strong></p>
                @foreach($subproductos as $k => $v)
                  <p class="card-text">{{ $v->nombre_mostrar }} <strong>{{ str_limit($calculo_sp[$v->id],6) }}</strong></p>
                @endforeach

              </div>
            </div>
          </div>
        </div>
    @endif
    @if( count($lecturas) > 0 )
      <hr>
      <div class="col-12 col-sm-12 col-md-12">
        <table class="table table-sm table-striped table-responsive-sm">
          <thead>
            <tr>
              <!--<th scope="col">#</th>-->
              <th scope="col">Balanza</th>
              <th scope="col">Lectura</th>
              <th scope="col">Lectura Acumulada</th>
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
    <!--<script type="text/javascript" src="{{ asset('js/balanza.verlecturas.js') }}"></script>-->
    <script type="text/javascript" src="{{ asset('js/balanza.calculos.js') }}"></script>
@endsection

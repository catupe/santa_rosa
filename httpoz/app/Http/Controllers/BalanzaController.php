<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;

class BalanzaController extends Controller
{
    private $rowsPerPage = 5;
    private $mensajes = array( '001' => 'La lectura es un campo requerido, debe ser num&eacute;rico',
                               '002' => 'La fecha es un campo requerido, formato yyyy-mm-dd hh:mm',
                               '003' => 'La balanza es un campo requerido' );
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->mensaje = app()->make('App\Http\Controllers\Mensaje');
    }

    public function getLecturas( Request $request )
    {
          try {
              /***
              ESTO ES DE EJEMPLO COMO REDIRIGIR SI LA FUNCION NO ES PERMITIDA
              $user = Auth::user();
              if (!$user->isRole('admin')) {
                  // TODO redirigir a index
              }
              ***/

              $codigo_error = 0;
              $mensajes     = array();
              $lecturas     = array();

              $balanza    = "";
              $fecha_ini  = "";
              $fecha_fin  = "";

              $start    = 0;
              $perPage  = $this->rowsPerPage;

              if( !isset($request->page) ) {
                  $request->page = 1;
              }
              if ( $request->page == 1) {
                  $start = 0;
              }
              else {
                  $start = ( $request->page - 1 ) * $perPage;
              }

              if( $request->has("page") ) {
                  $balanza   = $request->session()->get("balanzas_verlecturas_balanza");
                  $fecha_ini = $request->session()->get("balanzas_verlecturas_fecha_ini");
                  $fecha_fin = $request->session()->get("balanzas_verlecturas_fecha_fin");
              }
              if ( $request->has('aceptar') ) {
                  $request->merge(['page' => 1]);
                  $request->session()->put('balanzas_verlecturas_balanza', $request->balanza);
                  $request->session()->put('balanzas_verlecturas_fecha_ini', $request->fecha_ini);
                  $request->session()->put('balanzas_verlecturas_fecha_fin', $request->fecha_fin);
              }
              if( $request->has('aceptar') or $request->has("page") ) {
                  error_log("VOY A TRAER LECTURAS");
                  error_log("balanza => #".$request->session()->get("balanzas_verlecturas_balanza")."#");
                  if( ($request->session()->get("balanzas_verlecturas_balanza")   !== null ) and
                      ($request->session()->get("balanzas_verlecturas_fecha_ini") !== null ) and
                      ($request->session()->get("balanzas_verlecturas_fecha_fin") !== null ) ) {

                    $balanza   = $request->session()->get("balanzas_verlecturas_balanza");
                    $fecha_ini = $request->session()->get("balanzas_verlecturas_fecha_ini");
                    $fecha_fin = $request->session()->get("balanzas_verlecturas_fecha_fin");

                    $lecturas = \App\BalanzaLectura::where('balanza_id', $balanza)
                                                    ->where('created_at', '>=', $fecha_ini)
                                                    ->where('created_at', '<=', $fecha_fin)
                                                    ->limit($perPage)
                                                    ->offset($start)
                                                    ->orderBy('updated_at', 'desc')
                                                    ->orderBy('id', 'asc')
                                                    //->toSql();
                                                    ->paginate($perPage);
                                                    //->get();
                  }
                  // sino  hay datos para los filtros ingresados
                  // muestro mensaje de que no hay datos
                  if( count($lecturas) == 0 ) {
                      $codigo_error = 2;
                      $mensajes[]   = $this->mensaje->getMensaje( "002" );//"No existen datos para los filtros ingresados";
                  }
              }


              $balanzas = \App\Balanza::where('activa', 1)
                                        ->orderBy('nombre_mostrar', 'asc')
                                        ->get();

              return view('balanza.verlecturas', array( 'code_error'      => $codigo_error,
                                                        'mensaje'         => $mensajes,
                                                        'balanzas'        => $balanzas,
                                                        'balanza_actual'  => $balanza,
                                                        'lecturas'        => $lecturas));
          }
          catch( Exception $e ) {
            error_log("EXCEPXCION");
            return view('balanza.verlecturas', array( 'code_error'  => 1,
                                                      'errors'      => $this->mensaje->getMensaje( "000" )));//"Ocurrio un error vuelva a intentarlo mas tarde."));
          }
    }
    public function editarLectura( Request $request )
    {
          try {
              /***
              ESTO ES DE EJEMPLO COMO REDIRIGIR SI LA FUNCION NO ES PERMITIDA
              $user = Auth::user();
              if (!$user->isRole('admin')) {
                  // TODO redirigir a index
              }
              ***/
              $error = 0;
              $mensajes = array();


              if( $request->modo == 1 ) { // estoy creando lectura
                $balanza = null;
                if( is_numeric($request->balanza) ) {
                  $balanza = \App\Balanza::find($request->balanza);
                  if( !isset($balanza->id) ){
                    $error = 1;
                    $mensajes[] = $this->mensaje->getMensaje( "004" );//$this->mensajes["003"];
                  }
                }
                else {
                  $error = 1;
                  $mensajes[] = $this->mensaje->getMensaje( "004" );//$this->mensajes["003"];
                }
                if( !is_numeric($request->lectura) ) {
                  $error = 1;
                  $mensajes[] = $this->mensajes["001"];
                }
                if( !preg_match('/^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}$/', $request->fecha) ) {
                  $error = 1;
                  $mensajes[] = $this->mensaje->getMensaje( "003" );
                }

                if( $error == 0 ) {
                  $lectura              = new \App\BalanzaLectura;
                  $lectura->balanza_id  = $balanza->id;
                  $lectura->lectura     = $request->lectura;

                  // voy a ver cual es la lectura anterior para calculara la cantidad de lecturas
                  $last_lectura = \App\BalanzaLectura::where('balanza_id', $balanza->id)
                                                    ->orderBy('created_at', 'desc')
                                                    ->limit(1);
                                                    //->toSql();

                  $lectura->lectura_acumulada = $request->lectura_acumulada;// Se calcula??? $request->lectura + $last_lectura->lectura_acumulada;
                  $lectura->lectura_cantidad  = $last_lectura->lectura_cantidad + 1;
                  $lectura->comentarios       = $request->comentarios;

                  $lectura->save();
                }
              }
              elseif( $request->modo == 2 ) { // estoy editando
                $balanza = null;
                if( is_numeric($request->id) ) {
                  $balanza_lectura = \App\BalanzaLectura::find($request->id);

                  if( !isset($balanza_lectura->id) ){
                    $error = 1;
                    $mensajes[] = $this->mensajes["003"];
                  }
                  else{
                    $balanza_lectura->comentarios = $request->comentarios;
                    $balanza_lectura->save();
                  }
                }
                else {
                  $error = 1;
                  $mensajes[] = $this->mensajes["003"];
                }
              }

              $error      = 0;
              $mensajes[] = $this->mensaje->getMensaje( "001" );

              return \Response::json( array('error'    => $error,
                                            'mensaje'  => $mensajes ) );
              /*
              if( !isset($request->page) ) {
                  $request->page = 1;
              }
              if ( $request->page == 1) {
                  $start = 0;
              }
              else {
                  $start = ( $request->page - 1 ) * $perPage;
              }

              if( $request->has("page") ) {
                  $balanza   = $request->session()->get("balanzas_verlecturas_balanza");
                  $fecha_ini = $request->session()->get("balanzas_verlecturas_fecha_ini");
                  $fecha_fin = $request->session()->get("balanzas_verlecturas_fecha_fin");
              }
              if ( $request->has('aceptar') ) {
                  $request->merge(['page' => 1]);
                  $request->session()->put('balanzas_verlecturas_balanza', $request->balanza);
                  $request->session()->put('balanzas_verlecturas_fecha_ini', $request->fecha_ini);
                  $request->session()->put('balanzas_verlecturas_fecha_fin', $request->fecha_fin);
              }
              if( $request->has('aceptar') or $request->has("page") ) {
                  $balanza   = $request->session()->get("balanzas_verlecturas_balanza");
                  $fecha_ini = $request->session()->get("balanzas_verlecturas_fecha_ini");
                  $fecha_fin = $request->session()->get("balanzas_verlecturas_fecha_fin");

                  $lecturas = \App\BalanzaLectura::where('balanza_id', $balanza)
                                                  ->where('updated_at', '>=', $fecha_ini)
                                                  ->where('updated_at', '<=', $fecha_fin)
                                                  ->limit($perPage)
                                                  ->offset($start)
                                                  ->orderBy('updated_at', 'desc')
                                                  ->orderBy('id', 'asc')
                                                  //->toSql();
                                                  ->paginate($perPage);
                                                  //->get();

                  // sino  hay datos para los filtros ingresados
                  // muestro mensaje de que no hay datos
                  if( count($lecturas) == 0 ) {
                      $codigo_error = 2;
                      $mensaje      = "No existen datos para los filtros ingresados";
                  }
              }


              $balanzas = \App\Balanza::where('activa', 1)
                                        ->orderBy('nombre_mostrar', 'asc')
                                        ->get();

              return view('balanza.verlecturas', array( 'code_error'      => $codigo_error,
                                                        'mensaje'         => $mensaje,
                                                        'balanzas'        => $balanzas,
                                                        'balanza_actual'  => $balanza,
                                                        'lecturas'        => $lecturas));
              */
          }
          catch( Exception $e ) {
            error_log("EXCEPXCION");
            return view('balanza.verlecturas', array( 'code_error'  => 1,
                                                      'mensaje'     => $this->mensaje->getMensaje( "000" )));
          }
    }
    public function calculo( Request $request ) {
          try {
              /***
              ESTO ES DE EJEMPLO COMO REDIRIGIR SI LA FUNCION NO ES PERMITIDA
              $user = Auth::user();
              if (!$user->isRole('admin')) {
                  // TODO redirigir a index
              }
              ***/

              $codigo_error     = 0;
              $mensaje          = "";
              $lecturas         = array();
              $lectura_balanzas = array();

              $fecha_ini  = "";
              $hora_fin   = "";
              $afrechillo = 0;
              $semolin    = 0;

              // inicializo variables
              $balanzas                   = array();
              $balanza_trigo              = array();
              $calculos_mostrar           = array();
              $cantidad_lecturas          = array();
              $cantidad_lecturas["total"] = 0;
              $peso_blzs                  = array();
              $harinas                    = array();
              $subtotal                   = 0;
              $subproducto                = 0;
              $ptjes_subproductos         = array();
              $sp_subproductos            = array();
              $valores_subproductos       = array();
              $subproductos               = array();

              $subproductos_db = \App\Subproducto::where('activa', 1)
                                              ->orderBy('nombre_mostrar', 'asc')
                                              ->get();

              foreach($subproductos_db as $k => $v) {
                $subproductos[$v->id] = $v;
              }

              if( $request->has('aceptar') ) {
                  $fecha_ini = $request->fecha_ini; //$request->session()->get("balanzas_verlecturas_fecha_ini");
                  $hora_fin  = $request->hora_fin;
                  for($i = 0; $i < count($request->id_sp); $i++) {
                    $valores_subproductos[$request->id_sp[$i]] = $request->valores_sp[$i];
                  }

                  $dt = Carbon::createFromFormat('Y-m-d H:i', $fecha_ini);
                  $fecha_fin = $dt->addMinutes($hora_fin)->format('Y-m-d H:i');

                  $balanzas = \App\Balanza::where('activa', 1)
                                            ->orderBy('nombre_mostrar', 'asc')
                                            ->get();

                  foreach ( $balanzas as $k => $v ) {
                    $lecturas1 = DB::table('balanza_lectura')->select('balanza_lectura.*')
                                                             ->where('balanza_lectura.created_at', '>=', $fecha_ini)
                                                             ->where('balanza_lectura.created_at', '<=', $fecha_fin)
                                                             ->where('balanza_lectura.balanza_id', '=', $v->id)
                                                             ->orderBy('balanza_lectura.created_at', 'asc')
                                                             ->limit(1);

                    $lecturas = DB::table('balanza_lectura')->select('balanza_lectura.*')
                                                            ->where('balanza_lectura.created_at', '>=', $fecha_ini)
                                                            ->where('balanza_lectura.created_at', '<=', $fecha_fin)
                                                            ->where('balanza_lectura.balanza_id', '=', $v->id)
                                                            ->orderBy('balanza_lectura.created_at', 'desc')
                                                            ->limit(1)
                                                            ->union($lecturas1)
                                                            //->toSql();
                                                            ->get();

                    $cant_lecturas = DB::table('balanza_lectura')->select(DB::raw('count(*) as cantidad'))
                                                                 ->where('balanza_lectura.created_at', '>=', $fecha_ini)
                                                                 ->where('balanza_lectura.created_at', '<=', $fecha_fin)
                                                                 ->where('balanza_lectura.balanza_id', '=', $v->id)
                                                                 ->get();

                    if( !$lecturas->isEmpty() ) {
                      $lecturas->nombre_balanza = $v->nombre;
                      $lectura_balanzas[$v->id] = $lecturas;
                    }

                    if( !$cant_lecturas->isEmpty() ) {
                      $cantidad_lecturas["cantidades"][$v->id]["total"]   = $cant_lecturas[0]->cantidad;
                      $cantidad_lecturas["cantidades"][$v->id]["nombre"]  = $v->nombre;
                      $cantidad_lecturas["total"]                         += $cant_lecturas[0]->cantidad;
                    }
                  }
                  // sino  hay datos para los filtros ingresados
                  // muestro mensaje de que no hay datos
                  if( count($lectura_balanzas) == 0 ) {
                      $codigo_error = 2;
                      $mensaje      = "No existen datos para los filtros ingresados";
                  }

                  if( $codigo_error == 0 ) {
                    // cheque que en el periodo esten las lecturas mayor y menor para cada balanza
                    foreach ( $balanzas as $k => $v ) {
                      if( !isset($lectura_balanzas[$v->id][0]->lectura_acumulada) or
                          !isset($lectura_balanzas[$v->id][1]->lectura_acumulada) ) {
                        $codigo_error = 2;
                        $mensaje      = "Faltan datos por completar la solicitud";
                        break;
                      }
                    }

                    if ( $codigo_error == 0 ) {
                      // obtengo la balanza que es trigo
                      $balanza_trigo = \App\Balanza::where('es_trigo', 1)
                                                    ->get();

                      // guardo en el array $peso_blzs las diferencias de pesos acumulados del periodo
                      foreach ( $balanzas as $k => $v ) {
                        $peso_blzs[$v->id] = $lectura_balanzas[$v->id][0]->lectura_acumulada - $lectura_balanzas[$v->id][1]->lectura_acumulada;
                      }
                      // chequeo que el peso de la balanza 1 (trigo) se mayor que cero
                      if( $peso_blzs[$balanza_trigo[0]->id] <= 0 ) {
                        $codigo_error = 1;
                        $peso_trigo   = $peso_blzs[$balanza_trigo[0]->id];
                        $mensaje      = "El peso acumulado para el periodo de la balanza 1 es menor o igual a 0 ( $peso_trigo Kg trigo )";
                      }
                      else {
                        // este es el peso de la balanza 1, ademas quita el peso de harina1 array
                        $harina1 = $peso_blzs[$balanza_trigo[0]->id];
                        unset($peso_blzs[$balanza_trigo[0]->id]);
                        foreach ( $peso_blzs as $k => $v ) {
                          $harinas[$k] = ( $v / $harina1 ) * 100; //  porcentaje harinas 2 a n
                        }
                        // subtotal es la suma de todos los porcentajes de harinas de 2 a n (balanzas 2 a n)
                        foreach ( $harinas as $k => $v ) {
                          $subtotal += $v;
                        }
                        // subproducto es la resta del porcentaje de harina1(balanza1 100%) - subtotal
                        $subproducto = 100 - $subtotal;

                        foreach($valores_subproductos as $k => $v) {
                          $ptjes_subproductos[$k] = $v / $harina1;
                        }
                        foreach ($ptjes_subproductos as $k => $v) {
                          $sp_subproductos[$k] = $ptjes_subproductos[$k] / $subproducto;
                        }

                        // guardo en sesion los calculos hasta ahora
                        /*
                        $request->session()->put('calculo_pesos_balanzas', $peso_blzs);
                        $request->session()->put('calculo_peso_balanza1', $harina1);
                        $request->session()->put('calculo_harinas', $harinas);
                        $request->session()->put('calculo_subtotal', $subtotal);
                        $request->session()->put('calculo_subproducto', $subproducto);
                        */
                        $calculos_mostrar["calculo_cantidades_lecturas"]  = $cantidad_lecturas;
                        $calculos_mostrar["calculo_pesos_balanzas"]       = $peso_blzs;
                        $calculos_mostrar["calculo_trigo"]                = $harina1;
                        $calculos_mostrar["calculo_ptjes_harina"]         = $harinas;
                        $calculos_mostrar["calculo_subtotal"]             = $subtotal;
                        $calculos_mostrar["calculo_subproducto"]          = $subproducto;
                        $calculos_mostrar["calculo_ptje"]                 = $ptjes_subproductos;
                        $calculos_mostrar["calculo_sp"]                   = $sp_subproductos;

                      }
                    }
                  }
              }
              else {
                 $fecha_ini = date('Y-m-d H:i', strtotime('-1 hours'));
              }

              return view('balanza.calculosubproductos', array_merge(
                                                            array(  'code_error'            => $codigo_error,
                                                                    'mensaje'               => $mensaje,
                                                                    'fecha_ini_actual'      => $fecha_ini,
                                                                    'hora_fin_actual'       => $hora_fin,
                                                                    'afrechillo'            => $afrechillo,
                                                                    'semolin'               => $semolin,
                                                                    'lecturas'              => $lectura_balanzas,
                                                                    'balanzas'              => $balanzas,
                                                                    'balanza_trigo'         => $balanza_trigo,
                                                                    'valores_subproductos'  => $valores_subproductos,
                                                                    'subproductos'          => $subproductos),
                                                            $calculos_mostrar));
          }
          catch( Exception $e ) {
            error_log("EXCEPXCION");
            return view('balanza.verlecturas', array( 'code_error'  => 1,
                                                      'mensaje'     => $this->mensaje->getMensaje( "000" )));
          }
    }


}

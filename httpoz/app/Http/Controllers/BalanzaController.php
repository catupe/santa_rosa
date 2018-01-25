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
              $mensaje      = "";
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
          }
          catch( Exception $e ) {
            error_log("EXCEPXCION");
            return view('balanza.verlecturas', array( 'code_error'  => 1,
                                                      'mensaje'     => "Ocurrio un error vuelva a intentarlo mas tarde."));
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
              error_log("LLEGUE");
              error_log(print_r($request->all(),1));
              $error = 0;
              $mensaje = "";
              if( $request->modo == 1 ) { // estoy creando lectura
                if( is_numeric($request->balanza) ) {
                  error_log("ok");
                  $balanza = \App\Balanza::find($request->balanza);
                  error_log("------");
                  error_log(print_r($balanza,1));
                  error_log("------");
                  if( !isset($balanza) ){
                    $error = 1;
                    $mensajes[] = $this->mensajes["003"];
                  }
                }
                else {
                  $error = 1;
                  $mensajes[] = $this->mensajes["003"];
                }
                if( !is_numeric($reques->lectura) ) {
                  $error = 1;
                  $mensajes[] = $this->mensajes["001"];
                }
                if( !preg_match('/^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}$/', $reques->fecha) ) {
                  $error = 1;
                  $mensajes[] = $this->mensajes["002"];
                }
              }
              elseif( $request->modo == 2 ) { // estoy editando
                error_log("EDICION");
                $balanza = null;
                if( is_numeric($request->id) ) {
                  error_log("ok");
                  $balanza_lectura = \App\BalanzaLectura::find($request->id)->first();
                  error_log("------");
                  error_log(print_r($balanza_lectura->id,1));
                  error_log("------");
                  if( !isset($balanza_lectura->id) ){
                    $error = 1;
                    $mensajes[] = $this->mensajes["003"];
                  }
                }
                else {
                  $error = 1;
                  $mensajes[] = $this->mensajes["003"];
                }
              }
              return Response::json( array('error'    => $error,
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
                                                      'mensaje'     => "Ocurrio un error vuelva a intentarlo mas tarde."));
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
              $calculos_mostrar           = array();
              $cantidad_lecturas          = array();
              $cantidad_lecturas["total"] = 0;
              $peso_blzs                  = array();
              $harinas                    = array();
              $subtotal                   = 0;
              $subproducto                = 0;
              $ptje_afrechillo            = 0;
              $ptje_semolin               = 0;
              $sp_afrechillo              = 0;
              $sp_semolin                 = 0;

              if( $request->has('aceptar') ) {
                  $fecha_ini = $request->fecha_ini; //$request->session()->get("balanzas_verlecturas_fecha_ini");
                  $hora_fin  = $request->hora_fin;
                  $afrechillo= $request->afrechillo;
                  $semolin   = $request->semolin;

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

                      //$harina1 = array_shift($peso_blzs); // este es el peso de la balanza 1, ademas quita el peso de harina1 array
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

                      $ptje_afrechillo = $afrechillo / $harina1;
                      $ptje_semolin = $semolin / $harina1;
                      $sp_afrechillo = $ptje_afrechillo / $subproducto;
                      $sp_semolin = $ptje_semolin / $subproducto;

                      // guardo en sesion los calculos hasta ahora
                      $request->session()->put('calculo_pesos_balanzas', $peso_blzs);
                      $request->session()->put('calculo_peso_balanza1', $harina1);
                      $request->session()->put('calculo_harinas', $harinas);
                      $request->session()->put('calculo_subtotal', $subtotal);
                      $request->session()->put('calculo_subproducto', $subproducto);
                      $request->session()->put('calculo_afrechillo', $afrechillo);
                      $request->session()->put('calculo_semolin', $semolin);
                      $request->session()->put('calculo_spafrechillo', $sp_afrechillo);
                      $request->session()->put('calculo_spsemolin', $sp_semolin);
                      /*
                      error_log("---pesos balanzas---");
                      error_log("peso balanza1 -> ".$harina1);
                      error_log(print_r($peso_blzs,1));
                      error_log("---pesos harinas---");
                      error_log(print_r($harinas,1));
                      error_log("subtotal -> " . $subtotal);
                      error_log("subproducto -> " . $subproducto);
                      error_log("sp_afrechillo -> " . $sp_afrechillo);
                      error_log("sp_semolin -> " . $sp_semolin);
                      error_log(print_r($cantidad_lecturas,1));
                      */
                      $calculos_mostrar["calculo_cantidades_lecturas"]  = $cantidad_lecturas;
                      $calculos_mostrar["calculo_pesos_balanzas"]       = $peso_blzs;
                      $calculos_mostrar["calculo_trigo"]                = $harina1;
                      $calculos_mostrar["calculo_ptjes_harina"]         = $harinas;
                      $calculos_mostrar["calculo_subtotal"]             = $subtotal;
                      $calculos_mostrar["calculo_subproducto"]          = $subproducto;
                      $calculos_mostrar["calculo_ptje_afrechillo"]      = $ptje_afrechillo;
                      $calculos_mostrar["calculo_ptje_semolin"]         = $ptje_semolin;
                      $calculos_mostrar["calculo_sp_afrechillo"]        = $sp_afrechillo;
                      $calculos_mostrar["calculo_sp_semolin"]           = $sp_semolin;

                      error_log(print_r($calculos_mostrar,1));
                    }
                  }
              }
              else {
                 $fecha_ini = date('Y-m-d H:i', strtotime('-1 hours'));
              }

              return view('balanza.calculosubproductos', array_merge(
                                                            array(  'code_error'      => $codigo_error,
                                                                    'mensaje'         => $mensaje,
                                                                    'fecha_ini_actual'=> $fecha_ini,
                                                                    'hora_fin_actual' => $hora_fin,
                                                                    'afrechillo'      => $afrechillo,
                                                                    'semolin'         => $semolin,
                                                                    'lecturas'        => $lectura_balanzas,
                                                                    'balanzas'        => $balanzas,
                                                                    'balanza_trigo'   => $balanza_trigo),
                                                            $calculos_mostrar));
          }
          catch( Exception $e ) {
            error_log("EXCEPXCION");
            return view('balanza.verlecturas', array( 'code_error'  => 1,
                                                      'mensaje'     => "Ocurrio un error vuelva a intentarlo mas tarde."));
          }
    }


}

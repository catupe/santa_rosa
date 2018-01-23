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

              if( $request->has('aceptar') ) {
                  $fecha_ini = $request->fecha_ini; //$request->session()->get("balanzas_verlecturas_fecha_ini");
                  $hora_fin  = $request->hora_fin;

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
                                                            ->union($lecturas1)//->get()
                                                            //->toSql();
                                                            ->get();

                    if( !$lecturas->isEmpty() ) {
                      $lecturas->nombre_balanza = $v->nombre;
                      $lectura_balanzas[$v->id] = $lecturas;
                    }

                  }
                  // sino  hay datos para los filtros ingresados
                  // muestro mensaje de que no hay datos
                  if( count($lectura_balanzas) == 0 ) {
                      $codigo_error = 2;
                      $mensaje      = "No existen datos para los filtros ingresados";
                  }

                  if( $codigo_error == 0 ) {
                    if( !isset($lectura_balanzas[1][0]->lectura_acumulada) or
                        !isset($lectura_balanzas[1][1]->lectura_acumulada) ) {
                      $codigo_error = 2;
                      $mensaje      = "Faltan datos por completar";
                    }
                    if( !isset($lectura_balanzas[2][0]->lectura_acumulada) or
                        !isset($lectura_balanzas[2][1]->lectura_acumulada) ) {
                      $codigo_error = 2;
                      $mensaje      = "Faltan datos por completar";
                    }
                    if( !isset($lectura_balanzas[3][0]->lectura_acumulada) or
                        !isset($lectura_balanzas[3][1]->lectura_acumulada) ) {
                      $codigo_error = 2;
                      $mensaje      = "Faltan datos por completar";
                    }

                    if ( $codigo_error == 0 ) {
                      $peso_blz_1 = $lectura_balanzas[1][0]->lectura_acumulada - $lectura_balanzas[1][1]->lectura_acumulada;
                      $peso_blz_2 = $lectura_balanzas[2][0]->lectura_acumulada - $lectura_balanzas[2][1]->lectura_acumulada;
                      $peso_blz_3 = $lectura_balanzas[3][0]->lectura_acumulada - $lectura_balanzas[3][1]->lectura_acumulada;
                      error_log("--------------------");
                      error_log(print_r($peso_blz_1,1));
                      error_log(print_r($peso_blz_2,1));
                      error_log(print_r($peso_blz_3,1));
                    }                    
                  }

              }
              else {
                 $fecha_ini = date('Y-m-d H:i', strtotime('-1 hours'));
              }

              return view('balanza.calculosubproductos', array( 'code_error'      => $codigo_error,
                                                                'mensaje'         => $mensaje,
                                                                'fecha_ini_actual'=> $fecha_ini,
                                                                'hora_fin_actual' => $hora_fin,
                                                                'lecturas'        => $lectura_balanzas));
          }
          catch( Exception $e ) {
            error_log("EXCEPXCION");
            return view('balanza.verlecturas', array( 'code_error'  => 1,
                                                      'mensaje'     => "Ocurrio un error vuelva a intentarlo mas tarde."));
          }
    }


}

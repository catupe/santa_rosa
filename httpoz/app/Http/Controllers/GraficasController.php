<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GraficasController extends Controller
{
    protected $mensaje = null;

    public function __construct() {
        $this->middleware('auth');
        $this->mensaje = app()->make('App\Http\Controllers\Mensaje');
    }

    public function getGraficaLecturas( Request $request ) {
      try{

          /*
          error_log("=======================");
          error_log(print_r($request->all(),1));
          error_log("=======================");
          */
          $error        = 0;
          $mensajes     = array();
          $lecturas     = array();

          $balanza      = "";
          $fecha_ini    = "";
          $fecha_fin    = "";

          $data         = array();

          $balanzas = \App\Balanza::where('activa', 1)
                                    ->orderBy('nombre_mostrar', 'asc')
                                    ->get();

          if ( $request->has('_token') ) {
              $balanza   = $request->balanza;
              $fecha_ini = $request->fecha_ini;
              $fecha_fin = $request->fecha_fin;

              $lecturas = \App\BalanzaLectura::where('balanza_id', $balanza)
                                              ->where('created_at', '>=', $fecha_ini)
                                              ->where('created_at', '<=', $fecha_fin)
                                              //->toSql();
                                              ->get();

              //error_log(print_r($lecturas,1));
              foreach( $lecturas as $k => $v ) {
                  //error_log("----------");
                  //error_log(print_r($v->created_at,1));
                  //error_log("----------");
                  //$date = new DateTime($v->created_at);
                  //error_log(print_r($date,1));
                  //error_log("----date------");
                  //$v->created_at = $date->format('Ymd');
                  $data[] = $v;
              }

          }
          else{
              return view('graficas.lecturas', array( 'error'           => 0,
                                                      'mensaje'         => array(),
                                                      'balanzas'        => $balanzas,
                                                      'balanza_actual'  => $balanza));
          }
          if( empty($data) ) {
              $mensajes[] = $this->mensaje->getMensaje( "002" );
          }


          error_log(print_r($data,1));

          return \Response::json( array('error'    => $error,
                                        'mensaje'  => $mensajes,
                                        'data'     => $data) );

      }
      catch( Exception $e ) {
        return view('graficas.lecturas', array( 'error'  => 1,
                                                'mensaje'      => array($this->mensaje->getMensaje( "000" )) ) );
      }
    }
}

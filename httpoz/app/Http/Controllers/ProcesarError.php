<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProcesarError extends Controller
{
    protected $mensaje = null;
    //
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

    public function loadError( Request $request ) {
      try{
        $dataIn = json_decode($request->data);

        $tipo	 = $dataIn->tipo;
        $data["error"]	= 'mensajes.error';
        $data["info"]	  = 'mensajes.info';
        $data["ok"] 	  = 'mensajes.success';



        //$mensaje = \App::call('Mensaje@getMensaje');;

        $errors = array();
        if( isset($dataIn->codigoMensaje) and
            ($dataIn->codigoMensaje != "") and
            is_numeric($dataIn->codigoMensaje)){

        	 $errors[] = $this->mensaje->getMensaje( $dataIn->codigoMensaje );

        }
        elseif( !is_array( $dataIn->mensajes ) and ( $dataIn->mensajes == "") ){
    		    $errors[] = $this->mensaje->getMensaje("000");
        }
        elseif( is_array( $dataIn->mensajes )) {
          $errors = $dataIn->mensajes;
        }
        if( $tipo == "error" ){
          $salida = view('mensajes.error', array( 'errors' => $errors));
        }
        elseif( $tipo == "info" ){
        	$salida = $twig->render($data[$tipo],array(	'mensajes' => $errors));
        }
        elseif( $tipo == "ok" ) {
          $salida = $twig->render($data[$tipo],array(	'mensajes' => $errors));
        }
        error_log("======================");
        error_log($salida);
        error_log("======================");
        return response()->json( array("data" => (String)$salida ));
        //echo (response()->view($data[$tipo], array(	'mensajes' => $errors)));
        //return \Response::json( $salida  );;
        //return \Response::json( $salida  );

      }
      catch( Exception $e ) {
        error_log("EXCEPXCION");
        return view('balanza.verlecturas', array( 'code_error'  => 1,
                                                  'mensaje'     => "Ocurrio un error vuelva a intentarlo mas tarde."));
      }
    }
}

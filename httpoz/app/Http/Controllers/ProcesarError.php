<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProcesarError extends Controller
{
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function loadError( Request $request ) {
      try{

      //  $data = $request->json('mensajes');
        //$data= json_decode($request->all());
        error_log("--------loadError--------");
        //$dataEncode = array_shift(array_keys($_GET));
        //$data = @file_get_contents('php://input');
        //error_log(print_r($_GET,1));
	      // $data = json_decode($_GET);
        //error_log(print_r($data,1));
        //error_log(print_r($request,1));
        error_log(print_r(request()->all(),1));
        //error_log(print_r(Request::get('mensaje'),1));
        //error_log(print_r(json_decode($request->getContent(), true),1));
        //error_log(print_r($request->error,1));
        //$a = \Request::json_decode($request->all());
        //error_log(print_r($a,1));
        error_log("--------loadError--------");

        //$dataEncode = array_shift(array_keys($request->all()));
        //$dataIn = json_decode($dataEncode);
        /*
        error_log("--------ERORR--------");
        error_log(print_r($dataIn,1));
        error_log("--------ERORR--------");
        */
        /*

        $mensaje = "";
        if(isset($dataIn->mensaje) and ($dataIn->mensaje != "")){
        	$mensaje = str_replace('_', ' ', $dataIn->mensaje);
        }

        $tipo	 = $dataIn->tipo;

       	$data["error"]	= 'mensajes/error.html';
        $data["info"]	= 'mensajes/info.html';
        $data["ok"] 	= 'mensajes/success.html';

        if(isset($dataIn->codigoMensaje) and ($dataIn->codigoMensaje != "")){
        	$mensaje = Mensajes::getMensaje($dataIn->codigoMensaje, array($dataIn->mensaje));
        }
        elseif($mensaje == ""){
    		$mensaje = Mensajes::getMensaje('024', array());
        }

        if($tipo == "error"){
        	$salida = $twig->render($data[$tipo],array(	'mensaje_error'		=> $mensaje,
        												'hay_error' => 1));
        }
        else{
        	$salida = $twig->render($data[$tipo],array(	'mensaje' => $mensaje));
        }

        echo $salida;
        */
      }
      catch( Exception $e ) {
        error_log("EXCEPXCION");
        return view('balanza.verlecturas', array( 'code_error'  => 1,
                                                  'mensaje'     => "Ocurrio un error vuelva a intentarlo mas tarde."));
      }
    }
}

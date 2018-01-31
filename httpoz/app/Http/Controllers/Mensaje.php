<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Mensaje extends Controller
{
    private static $mensajes = array(
                                      "000" => "En este momento no se puede procesar su solicitud. Vuelva a intentarlo mas tarde",
                                      "001" => "Se proceso su solicitud correctamente",
                                      "002" => "No existen datos para los filtros ingresados",
                                      "003" => 'La fecha es un campo requerido, formato yyyy-mm-dd hh:mm',
                                      "004" => 'La balanza es un campo requerido',
                                    );

    public function getMensaje( $codigoMensaje = "000", $params = array() ) {
      $mensaje = self::$mensajes[$codigoMensaje];
      foreach($params as $k => $v){
          $mensaje = preg_replace("/#$k#/", $v, $mensaje);
      }
      return $mensaje;
    }
}

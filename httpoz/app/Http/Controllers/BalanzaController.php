<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BalanzaController extends Controller
{
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
              error_log("antes del view");
              $balanzas = \App\Balanza::where('activa', 1)
                                        ->orderBy('nombre_mostrar', 'asc')
                                        ->get();
              return view('balanza.verlecturas', array( 'balanzas'        => $balanzas,
                                                        'balanza_actual'  => 0));
          }
          catch( Exception $e ) {
            return view('balanza.verlecturas', array( 'code_error'  => 1,
                                                      'mensaje'     => "Ocurrio un error vuelva a intentarlo mas tarde."));
          }
    }
}

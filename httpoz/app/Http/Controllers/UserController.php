<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function cambiar_password( Request $request)
    {
        try
        {
            if ($request->has('aceptar'))
            {
                $this->validate(request(), [
                    'pass_vieja' => 'required',
                    'pass_nueva' => 'required|string|min:6',
                ]);
                // chequeo que la password vieja sea correcta
                if (!Hash::check($request->pass_vieja, $request->user()->password)) {
                  return view('auth.cambiarpassword', array('code_error'  => 1,
                                                            'mensaje'     => "La contrase&ntilde;a actual no es correcta"));
                }
                error_log("2");
                // chequeo que la nuva pass y la confirmacion sean iguales
                if(strcmp($request->pass_nueva, $request->pass_nueva_confirmation) == 0)
                {
                    request()->user()->fill([
                        'password' => Hash::make(request()->input('pass_nueva'))
                    ])->save();
                    error_log("3");
                    request()->session()->flash('success', 'Password changed!');
                    error_log("4");

                    return view('auth.cambiarpassword', array('code_error'  => 2,
                                                              'mensaje'     => "La contrase&ntilde;ia se cambio exitosamente"));
                }
                else
                {
                  return view('auth.cambiarpassword', array('code_error'  => 1,
                                                            'mensaje'     => "La contrase&ntilde;a nueva no coincide con la confirmaci&oacute;n"));
                }
              }
              return view('auth.cambiarpassword');
          }
          catch(Exception $e)
          {
              return view('auth.cambiarpassword', array('code_error'  => 1,
                                                        'mensaje'     => "Ocurrio un error vuelva a intentarlo mas tarde."));
          }
    }
}

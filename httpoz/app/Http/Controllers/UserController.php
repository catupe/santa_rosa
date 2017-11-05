<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        error_log(print_r($request->has('aceptar'),1));
        if ($request->has('aceptar'))
        {
            /*
            $this->validate(request(), [
                'pass_vieja' => 'required|current_password',
                'pass_nueva' => 'required|string|min:6|confirmed',
            ]);

            request()->user()->fill([
                'password' => Hash::make(request()->input('new_password'))
            ])->save();
            request()->session()->flash('success', 'Password changed!');
            */
            return view('auth.cambiarpassword', array('code_error'  => 1,
                                                      'mensaje'     => "hicieron post todo OK"));
        }
        //else{
        /*
          $this->validate(request(), [
              'pass_vieja' => 'required|current_password',
              'pass_nueva' => 'required|string|min:6|confirmed',
          ]);

          request()->user()->fill([
              'password' => Hash::make(request()->input('new_password'))
          ])->save();
          request()->session()->flash('success', 'Password changed!');
          */
          //return redirect()->route('auth.cambiarpassword');
          //return view('auth.cambiarpassword', array('code_error'  => 2,
          //                                          'mensaje'     => "hicieron post"));
          //}
          return view('auth.cambiarpassword');
    }
}

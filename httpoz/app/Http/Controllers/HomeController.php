<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
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
    public function index()
    {
        return view('ejemplo.ppal');
        /*
        $nombre_excel = storage_path('excel' . DIRECTORY_SEPARATOR . $nombre;);
        //$nombre_excel = 'storage' . DIRECTORY_SEPARATOR .'excel' . DIRECTORY_SEPARATOR . $nombre;
        Excel::load( $nombre_excel, function($reader) {
          // Loop through all sheets
          $reader->each(function($sheet) {

            // Loop through all rows
            $sheet->each(function($row) {
                error_log(print_r($row,1));
            });

          });
        });

        return view('home');
        */
    }
}

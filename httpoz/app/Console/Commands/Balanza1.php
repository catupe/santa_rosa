<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
//use \App\Balanza1 as bal1;

class Balanza1 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'balanza1:cargardatos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'La actualizacion de datos de la balanza 1 se realizo con exito';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //$balanza1 = Balanza1::where('name', '=', 'balanza 1');
        $balanza1 = new \App\Balanza1;
        $balanza1->lectura = "111";
        $balanza1->comentarios = "desde el job";
        $balanza1->created_at =  "2017-11-15 16:15:10";
        $balanza1->save();

        $now = new \DateTime();
        $fecha_actual = $now->format('d-m-Y H:i:s');
        $mensaje_salida = "[ " . $fecha_actual . " ]  -  balanza 1 -  " . $this->description;
        $this->info($mensaje_salida);
        /*
        $nombre_excel = 'storage' . DIRECTORY_SEPARATOR .'excel' . DIRECTORY_SEPARATOR . 'CE1.csv';
        Excel::load( $nombre_excel, function($reader) {
          // Loop through all sheets
          $reader->each(function($sheet) {

            // Loop through all rows
            $sheet->each(function($row) {
                error_log(print_r($row,1));
            });

          });
        });
        */
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
//use \App\Balanza1 as bal1;
use Maatwebsite\Excel\Facades\Excel;

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

        $cant_registros = 0;
        $ultima_actualizada = \App\Balanza1::max('fila');
        //var_dump($ultima_actualizada);
        $nombre_excel = storage_path( 'excel' . DIRECTORY_SEPARATOR . 'CE1.csv' );//'storage' . DIRECTORY_SEPARATOR .'excel' . DIRECTORY_SEPARATOR . 'CE1.csv';
        Excel::load( $nombre_excel, function($reader) use($ultima_actualizada, &$total, &$cant_registros) {
            $total = $reader->get()->count();

            $header = $reader->all()->first()->keys()->toArray();
            $cant_leer = $total - $ultima_actualizada;
            //var_dump($cant_leer);
            $reader->skipRows($ultima_actualizada);
            $datos = $reader->toArray();
            //var_dump($datos);
            //var_dump($cant_registros);
            $fila = $ultima_actualizada;
            for($i=0; $i<$cant_leer; $i++) {
                $fila ++;

                $balanza1 = new \App\Balanza1;
                //$balanza2 = new \App\Balanza2;
                //$balanza3 = new \App\Balanza3;

                $balanza1->lectura = trim($datos[$i][$header[0]]);
                //$balanza2->lectura = trim($datos[$i][$header[1]]);
                //$balanza3->lectura = trim($datos[$i][$header[2]]);

                $balanza1->comentarios = "";
                //$balanza2->comentarios = "";
                //$balanza3->comentarios = "";

                $dia_lectura = $datos[$i][$header[3]];
                $hora_lectura = $datos[$i][$header[4]];
                $balanza1->created_at = $dia_lectura . ' ' . $hora_lectura;
                //$balanza2->created_at = $dia_lectura . ' ' . $hora_lectura;
                //$balanza3->created_at = $dia_lectura . ' ' . $hora_lectura;

                $balanza1->fila = $fila;
                //$balanza2->fila = $fila;
                //$balanza3->fila = $fila;

                $balanza1->save();
                //$balanza2->save();
                //$balanza3->save();

                $cant_registros++;
            }
        });

        $now = new \DateTime();
        $fecha_actual = $now->format('d-m-Y H:i:s');
        $mensaje_salida  = "[ " . $fecha_actual . " ]  -  balanza 1 -  " . $this->description ."\n";
        $mensaje_salida .= "[ " . $fecha_actual . " ]  -  cantidad de datos ingresados -  " . $cant_registros ."\n";
        $mensaje_salida .= "\n\n";
        $this->info($mensaje_salida);

    }
}

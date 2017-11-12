<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class Balanza extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'balanza:cargardatos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Constante define cantidad de balnzas en el csv.
     *
     * @var integer
     */
    protected $cantidad_balanzas = 3;

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
        $total          = 0;
        $ultima_actualizada = \App\BalanzaLectura::max('fila');

        //print "ultima actualizada ".$ultima_actualizada."\n";

        $nombre_excel = storage_path( 'excel' . DIRECTORY_SEPARATOR . 'CE1.csv' );
        Excel::load( $nombre_excel, function($reader) use($ultima_actualizada, &$total, &$cant_registros) {
            $total = $reader->get()->count();

            $header = $reader->all()->first()->keys()->toArray();

            //// cargo los ids de las balanzas
            $arr_ids_balanzas = array();
            foreach ($header as $key => $value) {
                $balanza = \App\Balanza::where('nombre', '=', $value)->first();
                if(isset($balanza) and ($balanza != null)) {
                    $arr_ids_balanzas[$value] = $balanza->id;
                }
            }

            $cant_leer = $total - $ultima_actualizada;
            //var_dump($cant_leer);
            $reader->skipRows($ultima_actualizada);
            $datos = $reader->toArray();

            //$fila = $ultima_actualizada;

            // normalizo el csv para tener [fila][balanza][lectura]
            //                             [fila][balanza][fecha]
            $csv_normalizado = array();
            foreach ($datos as $key => $value) {
                foreach ($arr_ids_balanzas as $k => $v) {
                    $nombre_balanza = "";
                    $csv_normalizado[$key][$k]["lectura"] = $value[$k];
                    $csv_normalizado[$key][$k]["date"] = $value["date"] . ' ' . $value["time"];
                }
            }
            //var_dump($csv_normalizado);
            foreach ($csv_normalizado as $fila => $value) {
                foreach ($value as $nombre_balanza => $datos) {
                    $balanza_lectura              = new \App\BalanzaLectura;
                    $balanza_lectura->lectura     = trim($datos["lectura"]);
                    $balanza_lectura->comentarios = "";
                    $balanza_lectura->created_at  = $datos["date"];
                    $balanza_lectura->balanza_id   = $arr_ids_balanzas[$nombre_balanza];
                    $balanza_lectura->fila        = $fila + 1 + $ultima_actualizada;
                    $balanza_lectura->save();
                    $cant_registros++;
                }
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

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Balanza extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'balanza:cargardatos {balanza}';

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
     * Constante define nombre de archivos csv.
     *
     * @var integer
     */
    protected static $NOMBRE_CSV_BLZ_1 = 'CE1.csv';
    protected static $NOMBRE_CSV_BLZ_2 = 'CE2.csv';
    protected static $NOMBRE_CSV_BLZ_3 = 'CE3.csv';

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
     * Este se debe de correr por cada csv de balanza
     *
     * @return mixed
     */
    public function handle(Request $request)
    {
        try{
          $balanza_       = $this->argument('balanza');
          $nombre_csv = "";
          if( strcmp("balanza1", $balanza_) == 0 ) {
            $nombre_balanza = self::$NOMBRE_CSV_BLZ_1;
          }
          elseif( strcmp("balanza2", $balanza_) == 0 ) {
            $nombre_balanza = self::$NOMBRE_CSV_BLZ_2;
          }
          if( strcmp("balanza3", $balanza_) == 0 ) {
            $nombre_balanza = self::$NOMBRE_CSV_BLZ_3;
          }
          ini_set('memory_limit', '-1');
          $cant_registros = 0;
          $total          = 0;
          $ultima_actualizada = \App\BalanzaLectura::max('fila');

          print "ultima actualizada ".$ultima_actualizada."\n";

          //$nombre_excel = storage_path( 'excel' . DIRECTORY_SEPARATOR . '29-11-17CE1.csv' );
          $nombre_excel = storage_path( 'excel' . DIRECTORY_SEPARATOR . $nombre_balanza/*'CE1.csv'*/ );
          Excel::load( $nombre_excel, function($reader) use($ultima_actualizada, &$total, &$cant_registros) {
              $total = $reader->get()->count();

              $header = $reader->all()->first()->keys()->toArray();

              // selecciono la balanza corresponduente a la corrida
              $balanza = \App\Balanza::where('id', '=', 1)->first();
              $idBalanza = $balanza->id;

              $cant_leer = $total - $ultima_actualizada;
              $reader->skipRows($ultima_actualizada);
              $datos = $reader->toArray();

              $fila         = $ultima_actualizada;
              $commit_cont  = 0;
              foreach ($datos as $key => $value) {

                  if( is_numeric(trim($value[$header[0]])) and
                      is_numeric(trim($value[$header[1]])) and
                      is_numeric(trim($value[$header[2]])) and
                      preg_match('/\d{4}\-\d{2}\-\d{2}/', $value[$header[3]]) and
                      preg_match('/\d{2}:\d{2}:\d{2}/', $value[$header[4]]) ) {

                      $fila++;

                      $balanza_lectura                    = new \App\BalanzaLectura;
                      $balanza_lectura->lectura           = trim($value[$header[0]]);//trim($datos["lectura"]);
                      $balanza_lectura->lectura_acumulada = trim($value[$header[1]]);
                      $balanza_lectura->lectura_cantidad  = trim($value[$header[2]]);
                      $balanza_lectura->comentarios       = "";
                      $balanza_lectura->created_at        = $value[$header[3]] . ' ' . $value[$header[4]];
                      $balanza_lectura->balanza_id        = $idBalanza;//$arr_ids_balanzas[$nombre_balanza];
                      $balanza_lectura->fila              = $fila;
                      $balanza_lectura->save();
                      $cant_registros++;

                      $commit_cont++;
                      if( $commit_cont == 500 ) {
                        print("comiteo => ".$commit_cont."\r\n");
                        $this->info("commit : ".$commit_cont . '\n\r');
                        $commit_cont = 0;
                        DB::commit();
                      }
                  }
                  else{
                      $nro_fila = $fila + 1;
                      $mensaje_salida = "Formato invalido fila: " . $nro_fila . "\n";
                      $this->info($mensaje_salida);
                  }
              }
              DB::commit();

          });

          $now = new \DateTime();
          $fecha_actual = $now->format('d-m-Y H:i:s');
          $mensaje_salida  = "[ " . $fecha_actual . " ]  -  balanza 1 -  " . $this->description ."\n\r";
          $mensaje_salida .= "[ " . $fecha_actual . " ]  -  cantidad de datos ingresados -  " . $cant_registros ."\n\r";
          $mensaje_salida .= "\n\n";
          $this->info($mensaje_salida);
        }
        catch( Exception $e ) {
          print "EXCEPCION => ".$e->getMessage();
        }
    }
}

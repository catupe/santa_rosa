<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

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
     * Este se debe de correr por cada csv de balanza
     *
     * @return mixed
     */
    public function handle()
    {
        try{
          ini_set('memory_limit', '-1');
          $cant_registros = 0;
          $total          = 0;
          $ultima_actualizada = \App\BalanzaLectura::max('fila');

          print "ultima actualizada ".$ultima_actualizada."\n";

          //$nombre_excel = storage_path( 'excel' . DIRECTORY_SEPARATOR . '29-11-17CE1.csv' );
          $nombre_excel = storage_path( 'excel' . DIRECTORY_SEPARATOR . 'CE1.csv' );
          Excel::load( $nombre_excel, function($reader) use($ultima_actualizada, &$total, &$cant_registros) {
              $total = $reader->get()->count();

              $header = $reader->all()->first()->keys()->toArray();
              //var_dump($header);
              //exit;
              // selecciono la balanza corresponduente a la corrida
              $balanza = \App\Balanza::where('id', '=', 1)->first();
              $idBalanza = $balanza->id;
              /*+++
              //// cargo los ids de las balanzas
              $arr_ids_balanzas = array();
              foreach ($header as $key => $value) {
                  $balanza = \App\Balanza::where('nombre', '=', $value)->first();
                  if(isset($balanza) and ($balanza != null)) {
                      $arr_ids_balanzas[$value] = $balanza->id;
                  }
              }
              +++*/
              $cant_leer = $total - $ultima_actualizada;
              //var_dump($cant_leer);
              $reader->skipRows($ultima_actualizada);
              $datos = $reader->toArray();

              //$fila = $ultima_actualizada;

              // normalizo el csv para tener [fila][balanza][lectura]
              //                             [fila][balanza][fecha]
              $csv_normalizado = array();
              foreach ($datos as $key => $value) {
                if(preg_match('/\d+\.?\d+/', $value[$k])) {
                  //$csv_normalizado[$key][$idBalanza]["lectura"] = $value[$k];
                  //$csv_normalizado[$key][$idBalanza]["date"]    = $value["date"] . ' ' . $value["time"];
                  $balanza_lectura                    = new \App\BalanzaLectura;
                  $balanza_lectura->lectura           = trim($value[$header[0]]);//trim($datos["lectura"]);
                  $balanza_lectura->lectura_acumulada = trim($value[$header[1]]);
                  $balanza_lectura->lectura_cantidad  = trim($value[$header[2]]);
                  $balanza_lectura->comentarios       = "";
                  $balanza_lectura->created_at        = $value[$header[3]] . ' ' . $value[$header[4]];
                  $balanza_lectura->balanza_id        = $idBalanza;//$arr_ids_balanzas[$nombre_balanza];
                  $balanza_lectura->fila              = $fila + 1 + $ultima_actualizada;
                  $balanza_lectura->save();
                  $cant_registros++;

                  $commit_cont++;
                  //error_log("cont => ".$commit_cont);
                  if( $commit_cont == 500 ) {
                    print("comiteo => ".$commit_cont."\r\n");
                    $this->info("commit : ".$commit_cont . '\n\r');
                    $commit_cont = 0;
                    DB::commit();
                  }
                }
              }
              /*++++
              foreach ($datos as $key => $value) {
                  foreach ($arr_ids_balanzas as $k => $v) {
                      $nombre_balanza = "";
                      if(preg_match('/\d+\.?\d+/', $value[$k])) {
                        $csv_normalizado[$key][$k]["lectura"] = $value[$k];
                        $csv_normalizado[$key][$k]["date"] = $value["date"] . ' ' . $value["time"];
                      }
                  }
              }
              +++*/
              //var_dump($csv_normalizado);
              /*
              $commit_cont = 0;
              DB::beginTransaction();
              foreach ($csv_normalizado as $fila => $value) {
                  foreach ($value as $nombre_balanza => $datos) {
                      //var_dump($datos);
                      $balanza_lectura              = new \App\BalanzaLectura;
                      $balanza_lectura->lectura     = trim($datos["lectura"]);
                      $balanza_lectura->comentarios = "";
                      $balanza_lectura->created_at  = $datos["date"];
                      $balanza_lectura->balanza_id  = $arr_ids_balanzas[$nombre_balanza];
                      $balanza_lectura->fila        = $fila + 1 + $ultima_actualizada;
                      $balanza_lectura->save();
                      $cant_registros++;

                      $commit_cont++;
                      //error_log("cont => ".$commit_cont);
                      if( $commit_cont == 500 ) {
                        print("comiteo => ".$commit_cont."\r\n");
                        $this->info("commit : ".$commit_cont . '\n\r');
                        $commit_cont = 0;
                        DB::commit();
                      }
                  }
              }
              DB::commit();
              */
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

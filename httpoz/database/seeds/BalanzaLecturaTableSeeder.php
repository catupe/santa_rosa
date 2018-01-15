<?php

use Illuminate\Database\Seeder;

class BalanzaLecturaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $balanza1 = App\Balanza::where('nombre', '=', 'balanza1')->first();
        \App\BalanzaLectura::create([
            'balnza_id'             => $balanza1->id,
            'lectura'               => '129.3',
            'lectura_acumulada'     => '129.3',
            'lectura_cantidad'      => '1',
            'comentarios'           => '',
            'fila'                  => '1'
        ]);
    }
}

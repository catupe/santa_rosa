<?php

use Illuminate\Database\Seeder;

class BalanzaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Balanza::create([
            'nombre' => 'balanza1',
            'nombre_mostrar' => 'Balanza 1',
            'descripcion' => 'Balanza 1, ztq 1029',
            'es_trigo'    => 1,
        ]);
        \App\Balanza::create([
            'nombre' => 'balanza2',
            'nombre_mostrar' => 'Balanza 2',
            'descripcion' => 'Balanza 2, ztq 1029'
        ]);
        \App\Balanza::create([
            'nombre' => 'balanza3',
            'nombre_mostrar' => 'Balanza 3',
            'descripcion' => 'Balanza 3, jkkr 2008'
        ]);
    }
}

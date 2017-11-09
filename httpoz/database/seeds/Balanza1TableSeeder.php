<?php

use Illuminate\Database\Seeder;

class Balanza1TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
     {
         \App\Balanza1::create([
             //'name' => 'balanza 1',
             'lectura' => '20134',
             'comentarios' => '',
             'fila' => 1,
         ]);
     }
}

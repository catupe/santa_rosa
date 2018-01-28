<?php

use Illuminate\Database\Seeder;

class SubproductoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      \App\Subproducto::create([
          'nombre' => 'afrechillo',
          'nombre_mostrar' => 'Afrechillo',
          'descripcion' => 'Afrechillo'
      ]);
      \App\Subproducto::create([
          'nombre' => 'semolin',
          'nombre_mostrar' => 'Semolin',
          'descripcion' => 'Semolin'
      ]);
    }
}

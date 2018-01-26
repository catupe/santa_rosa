<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subproducto extends Model
{
  protected $table = 'subproducto';
  protected $fillable = ['nombre', 'nombre_mostrar', 'descripcion'];
}

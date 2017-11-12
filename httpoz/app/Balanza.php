<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Balanza extends Model
{
    protected $table = 'balanza';
    protected $fillable = ['nombre', 'nombre_mostrar', 'descripcion'];
}

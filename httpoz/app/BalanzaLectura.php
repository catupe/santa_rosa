<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BalanzaLectura extends Model
{
    protected $table = 'balanza_lectura';
    protected $fillable = ['lectura', 'comentarios', 'fila'];
}

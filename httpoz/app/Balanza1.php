<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Balanza1 extends Model
{
  protected $table = 'balanza1';
  protected $fillable = ['name', 'lectura', 'comentarios'];
}

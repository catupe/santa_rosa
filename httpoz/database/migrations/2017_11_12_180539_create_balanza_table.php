<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBalanzaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('balanza');
        Schema::create('balanza', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre')->unique();
            $table->string('nombre_mostrar');
            $table->string('descripcion');
            $table->smallInteger('activa')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('balanza');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBalanzaLecturaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('balanza_lectura');
        Schema::create('balanza_lectura', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('balnza_id')->unsigned();
            $table->foreign('balnza_id')->references('id')->on('balanza');
            $table->decimal('lectura', 20, 8);
            $table->string('comentarios');
            $table->unsignedInteger('fila');
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
        Schema::dropIfExists('balanza_lectura');
    }
}

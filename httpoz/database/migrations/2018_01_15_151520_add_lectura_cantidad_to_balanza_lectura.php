<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLecturaCantidadToBalanzaLectura extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('balanza_lectura', function (Blueprint $table) {
            $table->unsignedInteger('lectura_cantidad')->after('lectura');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('balanza_lectura', function (Blueprint $table) {
            //
        });
    }
}

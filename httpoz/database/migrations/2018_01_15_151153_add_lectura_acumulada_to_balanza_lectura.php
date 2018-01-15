<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLecturaAcumuladaToBalanzaLectura extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('balanza_lectura', function (Blueprint $table) {
            $table->decimal('lectura_acumulada', 20, 8)->after('lectura');
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

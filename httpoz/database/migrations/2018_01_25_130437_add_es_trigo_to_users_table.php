<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEsTrigoToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('balanza', function (Blueprint $table) {
            //$table->unsignedInteger('lectura_cantidad')->after('lectura_acumulada');
            $table->boolean('es_trigo')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('balanza', function (Blueprint $table) {
            //
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBalanza1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('balanza1');
        Schema::create('balanza1', function (Blueprint $table) {
            $table->increments('id');
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
        Schema::dropIfExists('balanza1');
    }
}

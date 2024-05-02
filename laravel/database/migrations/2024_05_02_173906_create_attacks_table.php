<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attacks', function (Blueprint $table) {
            $table->id(); // Crea una columna de ID autoincrementable.
            $table->string('name'); // Crea una columna para el nombre del ataque.
            $table->integer('power'); // Crea una columna para la potencia del ataque como número entero.
            $table->timestamps(); // Crea las columnas 'created_at' y 'updated_at'.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attacks'); // Elimina la tabla si se revierte la migración.
    }
}

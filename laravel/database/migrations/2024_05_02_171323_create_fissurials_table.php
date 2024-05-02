<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFissurialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fissurials', function (Blueprint $table) {
            $table->id(); // Crea una columna de ID autoincrementable.
            $table->string('name'); // Crea una columna para el nombre.
            $table->string('photo'); // Crea una columna para el path de la foto.
            $table->integer('original_life'); // Crea una columna para la vida original como número entero.
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
        Schema::dropIfExists('fissurials'); // Elimina la tabla si se revierte la migración.
    }
}

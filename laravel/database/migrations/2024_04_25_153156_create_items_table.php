<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id(); // Crea una columna de ID autoincrementable.
            $table->string('name'); // Crea una columna para el nombre del item.
            $table->string('photo'); // Crea una columna para el path de la foto del item.
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
        Schema::dropIfExists('items'); // Elimina la tabla si se revierte la migración.
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('backpacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');  // Clave foránea que apunta a 'users'
            $table->unsignedBigInteger('item_id');  // Clave foránea que apunta a 'items'
            $table->integer('quantity');  // Cantidad de objetos
            $table->timestamps();

            // Establece la relación con la tabla 'users'
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Establece la relación con la tabla 'items'
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backpacks');
    }
};

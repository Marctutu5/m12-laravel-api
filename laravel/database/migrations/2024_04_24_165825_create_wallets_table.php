<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();  // ID único para cada wallet
            $table->unsignedBigInteger('user_id');  // Suponiendo que cada wallet está vinculado a un usuario
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('coins')->default(0);  // Número de monedas, con un valor por defecto de 0
            $table->timestamps();  // Fechas de creación y actualización de cada wallet
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallets');
    }
};

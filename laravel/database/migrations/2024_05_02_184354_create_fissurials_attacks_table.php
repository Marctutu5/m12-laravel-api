<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFissurialsAttacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fissurials_attacks', function (Blueprint $table) {
            $table->unsignedBigInteger('fissurial_id'); // Columna para el ID de fissurial
            $table->unsignedBigInteger('attack_id'); // Columna para el ID de attack
            $table->timestamps();

            // Establece la clave foránea hacia la tabla fissurials
            $table->foreign('fissurial_id')->references('id')->on('fissurials')->onDelete('cascade');

            // Establece la clave foránea hacia la tabla attacks
            $table->foreign('attack_id')->references('id')->on('attacks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fissurials_attacks');
    }
}

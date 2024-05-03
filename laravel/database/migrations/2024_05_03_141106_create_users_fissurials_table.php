<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersFissurialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_fissurials', function (Blueprint $table) {
            $table->id(); // Columna ID autoincrementable
            $table->unsignedBigInteger('user_id'); // Columna para el ID de user
            $table->unsignedBigInteger('fissurials_id'); // Columna para el ID de fissurial
            $table->integer('current_life'); // Columna para la vida actual de fissurial en la partida
            $table->timestamps();

            // Clave foránea hacia la tabla users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Clave foránea hacia la tabla fissurials
            $table->foreign('fissurials_id')->references('id')->on('fissurials')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_fissurials');
    }
}

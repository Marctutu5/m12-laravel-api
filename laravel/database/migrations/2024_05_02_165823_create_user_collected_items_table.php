<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCollectedItemsTable extends Migration
{
    public function up()
    {
        Schema::create('user_collected_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('map_item_id');
            $table->foreign('map_item_id')->references('id')->on('map_items');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->boolean('collected')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_collected_items');
    }
}

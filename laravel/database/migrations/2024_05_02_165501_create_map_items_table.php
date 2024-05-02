<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMapItemsTable extends Migration
{
    public function up()
    {
        Schema::create('map_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items');
            $table->integer('x');
            $table->integer('y');
            $table->string('scene');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('map_items');
    }
}

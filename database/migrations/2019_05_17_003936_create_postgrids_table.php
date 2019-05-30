<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostgridsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grids', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('type')->default('packery');
            $table->text('options')->nullable();
            $table->timestamps();
        });

        Schema::create('elements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('grid_id')->unsigned();
            $table->text('options')->nullable();
            $table->morphs('elementable');
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
        Schema::dropIfExists('grids');
        Schema::dropIfExists('elements');
    }
}

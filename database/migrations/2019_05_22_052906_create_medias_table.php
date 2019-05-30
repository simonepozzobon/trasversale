<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('src');
            $table->string('thumb');
            $table->string('thumb_md');
            $table->string('landscape');
            $table->timestamps();
        });

        Schema::create('mediables', function (Blueprint $table) {
            $table->bigIncrements('media_id')->unsigned();
            $table->morphs('mediable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medias');
        Schema::dropIfExists('mediables');
    }
}

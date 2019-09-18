<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('has_limited_guests')->after('price')->default(0);
            $table->integer('guests_total')->after('has_limited_guests')->default(0);
            $table->integer('guests_available')->after('guests_total')->default(0);
            $table->integer('guests_confirmed')->after('guests_available')->default(0);
        });

        Schema::create('guests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->unsigned();
            $table->bigInteger('subscriber_id')->unsigned();
            $table->integer('status')->unsigned();
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
        Schema::dropIfExists('guests');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('has_limited_guests');
            $table->dropColumn('guests_total');
            $table->dropColumn('guests_available');
            $table->dropColumn('guests_confirmed');
        });
    }
}

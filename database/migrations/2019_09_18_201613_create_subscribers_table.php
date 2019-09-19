<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->bigInteger('subscriber_id')->after('id')->unsigned();
            $table->integer('payment_status_id')->after('subscriber_id')->default(4);
            $table->string('email')->after('surname');
        });

        Schema::create('subscribers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('surname');
            $table->string('email');
            $table->bigInteger('payment_type_id')->unsigned();
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
        Schema::dropIfExists('subscribers');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('subscriber_id');
            $table->dropColumn('payment_status_id');
            $table->dropColumn('email');
        });
    }
}

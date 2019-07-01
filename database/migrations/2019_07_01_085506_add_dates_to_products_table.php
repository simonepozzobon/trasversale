<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDatesToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'products', function (Blueprint $table) {
                $table->dateTime('start_at')->after('price')->nullable();
                $table->dateTime('end_at')->after('start_at')->nullable();
                $table->integer('hours')->after('end_at')->nullable()->default(0);
                $table->string('address')->after('hours')->nullable();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'products', function (Blueprint $table) {
                $table->dropColumn('start_at');
                $table->dropColumn('end_at');
                $table->dropColumn('hours');
                $table->dropColumn('address');
            }
        );
    }
}

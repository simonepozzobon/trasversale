<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTacherCardPaymentToProductsTable extends Migration
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
                $table->boolean('teacher_card_payment')->default(false)->after('price');
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
                $table->dropColumn('teacher_card_payment');
            }
        );
    }
}

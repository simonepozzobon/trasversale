<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVatToProductsTable extends Migration
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
                $table->string('vat')->after('price')->default(0);
                $table->boolean('vat_included')->after('vat')->default(0);
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
                $table->dropColumn('vat');
                $table->dropColumn('vat_included');
            }
        );
    }
}

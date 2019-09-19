19<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('ragione_sociale')->after('address')->nullable();
            $table->string('cf')->after('ragione_sociale')->nullable();
            $table->string('codice_destinatario')->after('cf')->nullable();
            $table->string('language')->after('codice_destinatario')->nullable();
            $table->string('pec')->after('language')->nullable();
            $table->string('phone')->after('pec')->nullable();
            $table->string('type')->after('phone')->nullable();
            $table->string('vat')->after('type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('ragione_sociale');
            $table->dropColumn('cf');
            $table->dropColumn('codice_destinatario');
            $table->dropColumn('language');
            $table->dropColumn('pec');
            $table->dropColumn('phone');
            $table->dropColumn('type');
            $table->dropColumn('vat');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_wirehouses', function (Blueprint $table) {
            $table->enum('purchase_type', ['Wholesale', 'Retail'])->default('Wholesale')->after('id_wirehouse');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_wirehouses', function (Blueprint $table) {
            //
        });
    }
};

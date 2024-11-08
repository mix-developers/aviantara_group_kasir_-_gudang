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
        Schema::table('order_wirehouse_items', function (Blueprint $table) {
            $table->integer('discount_persen')->default(0)->after('quantity');
            $table->integer('discount_rupiah')->default(0)->after('discount_persen');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_wirehouse_items', function (Blueprint $table) {
            //
        });
    }
};

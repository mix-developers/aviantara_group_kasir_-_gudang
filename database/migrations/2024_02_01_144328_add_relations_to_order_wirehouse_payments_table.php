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
        Schema::table('order_wirehouse_payments', function (Blueprint $table) {

            $table->foreign('id_order_wirehouse')->references('id')->on('order_wirehouses');
            $table->foreign('id_payment_method')->references('id')->on('payment_methods');
            $table->foreign('id_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_wirehouse_payments', function (Blueprint $table) {
            //
        });
    }
};

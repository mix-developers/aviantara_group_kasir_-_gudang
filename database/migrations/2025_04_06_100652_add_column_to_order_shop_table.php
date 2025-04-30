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
        Schema::table('order_shop', function (Blueprint $table) {
            $table->integer('payment_received')->default(0)->after('total_fee');
            $table->integer('change')->default(0)->after('payment_received');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_shop', function (Blueprint $table) {
            //
        });
    }
};
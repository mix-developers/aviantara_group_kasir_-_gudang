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
        Schema::table('payment_method_items', function (Blueprint $table) {
            $table->foreignId('id_order_wirehouse')->nullable()->after('id')->constrained('order_wirehouses') // References `id` on `order_wirehouses` table
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_method_items', function (Blueprint $table) {
            //
        });
    }
};

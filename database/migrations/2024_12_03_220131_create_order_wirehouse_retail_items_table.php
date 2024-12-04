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
        Schema::create('order_wirehouse_retail_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_order_wirehouse');
            $table->foreignId('id_product');
            $table->date('expired_date');
            $table->integer('quantity')->default(1);
            $table->integer('discount_persen')->default(0);
            $table->integer('discount_rupiah')->default(0);
            $table->integer('price');
            $table->integer('subtotal');
            $table->timestamps();

            $table->foreign('id_order_wirehouse')->references('id')->on('order_wirehouses');
            $table->foreign('id_product')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_wirehouse_retail_items');
    }
};
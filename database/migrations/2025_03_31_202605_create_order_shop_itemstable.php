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
        Schema::create('order_shop_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_order_shiop')->constrained('order_shop')->onDelete('cascade');
            $table->foreignId('id_product')->constrained('product_shop')->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('price');
            $table->integer('discount')->default(0);
            $table->float('discount_rupiah', 10, 2)->default(0);
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_shop_itemstable');
    }
};
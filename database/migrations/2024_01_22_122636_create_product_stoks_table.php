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
        Schema::create('product_stoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_product');
            $table->foreignId('id_user');
            $table->enum('type', ['Masuk', 'Keluar']);
            $table->integer('quantity');
            $table->date('expired_date');
            $table->timestamps();

            $table->foreign('id_product')->references('id')->on('products');
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
        Schema::dropIfExists('product_stoks');
    }
};

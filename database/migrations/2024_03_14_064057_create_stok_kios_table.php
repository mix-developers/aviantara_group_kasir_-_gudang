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
        Schema::create('stok_kios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kios');
            $table->foreignId('id_product');
            $table->foreignId('id_user');
            $table->enum('type', ['Masuk', 'Keluar']);
            $table->integer('qty');
            $table->integer('price');
            $table->date('expired_date');
            $table->string('description');
            $table->timestamps();

            $table->foreign('id_kios')->references('id')->on('shops');
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
        Schema::dropIfExists('stok_kios');
    }
};

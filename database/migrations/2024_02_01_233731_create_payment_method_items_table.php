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
        Schema::create('payment_method_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_payment_method');
            $table->foreignId('id_user');
            $table->integer('paid');
            $table->text('description')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('payment_method_items');
    }
};

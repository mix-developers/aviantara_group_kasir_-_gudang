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
        Schema::create('order_wirehouses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user');
            $table->foreignId('id_customer');
            $table->foreignId('id_wirehouse');
            $table->string('total_fee');
            $table->string('additional_fee')->default(0);
            $table->boolean('delivery')->default(0);
            $table->string('address_delivery')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_customer')->references('id')->on('customers');
            $table->foreign('id_wirehouse')->references('id')->on('wirehouses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_wirehouses');
    }
};

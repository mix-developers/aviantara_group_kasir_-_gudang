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
        Schema::create('order_shop', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_shop')->constrained('shops')->onDelete('cascade');
            $table->string('no_invoice');
            $table->integer('total_fee');
            $table->integer('fee');
            $table->integer('additional_fee')->default(0);
            $table->float('discount', 10, 2)->default(0);
            $table->float('discount_rupiah', 10, 2)->default(0);
            $table->text('description')->nullable();
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
        Schema::dropIfExists('order_shop');
    }
};
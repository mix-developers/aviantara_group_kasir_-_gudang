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
        Schema::table('product_shop', function (Blueprint $table) {
            $table->string('name');
            $table->enum('unit', ['Karung', 'Karton', 'Bal', 'Pak', 'Koli', 'Kg']);
            $table->enum('sub_unit', ['Kg', 'Gram', 'Ons', 'Liter', 'Pcs', 'Kodi', 'Lembar', 'Butir', 'Bungkus', 'Lusin', 'Gros', 'Ekor', 'Buah', 'Sacet', 'Botol', 'Kaleng', 'Gelas']);
            $table->string('barcode')->unique();
            $table->integer('quantity_unit');
            $table->string('photo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_shop', function (Blueprint $table) {
            //
        });
    }
};
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
        Schema::table('products', function (Blueprint $table) {
            $table->enum('sub_unit', ['Pcs', 'Ekor', 'Buah', 'Sacet', 'Botol', 'Gelas', 'Butir', 'Rim', 'Lembar', 'Gross', 'Lusin', 'Kodi', 'Bungkus', 'Kg'])->after('unit')->default('Pcs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};

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
        Schema::table('product_damageds', function (Blueprint $table) {
            $table->renameColumn('total', 'quantity_unit');
            $table->integer('quantity_sub_unit')->nullable();
            $table->string('photo2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_damageds', function (Blueprint $table) {
            //
        });
    }
};

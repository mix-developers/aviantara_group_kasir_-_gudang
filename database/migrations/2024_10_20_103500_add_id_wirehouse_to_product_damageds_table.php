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
            $table->foreignId('id_wirehouse')
                ->after('id_product')
                ->nullable()
                ->constrained('wirehouses')
                ->onDelete('cascade');
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

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
        Schema::create('wirehouse_opname_schedule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_wirehouse')->constrained('wirehouses')->onDelete('cascade');
            $table->integer('date_schedule');
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
        Schema::dropIfExists('wirehouse_opname_schedule');
    }
};
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
        Schema::table('wirehouses', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('address');
            $table->string('ud_cv')->default('AVIANTARA GROUP')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wirehouses', function (Blueprint $table) {
            //
        });
    }
};
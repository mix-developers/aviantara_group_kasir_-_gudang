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
        Schema::table('wirehouse_opname_item', function (Blueprint $table) {
            $table->integer('selisih_retail')->default(0)->after('selisih');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wirehouse_opname_item', function (Blueprint $table) {
            //
        });
    }
};

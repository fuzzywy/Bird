<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKpiformula2GTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpiformula_2_g', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kpiName',50);
            $table->string('user',255);
            $table->string('kpiFormula',3000);
            $table->integer('kpiPrecision');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kpiformula_2_g');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKpiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('date_id');
            $table->string('hour_id');
            $table->string('min_id');
            $table->string('operator');
            $table->string('province');
            $table->string('system');
            $table->string('kpi');
            $table->string('spell-kpi');
            $table->string('data');
            $table->string('brforeData');
            $table->string('difference');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kpi');
    }
}

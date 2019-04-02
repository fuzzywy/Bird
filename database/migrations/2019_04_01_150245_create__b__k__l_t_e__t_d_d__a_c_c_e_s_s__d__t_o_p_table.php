<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBKLTETDDACCESSDTOPTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('B_K_LTE_TDD_ACCESS_D_TOP', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('day_id');
            $table->tinyInteger('hour_id');
            $table->text('province')->comment('省份');
            $table->text('city')->comment('地市');
            $table->text('cell')->comment('小区名');
            $table->integer('c_access_fail')->comment('接入失败次数');
            $table->integer('c_history')->comment('过去14天恶化小时数');
            $table->integer('c_now')->comment('当天恶化小时数');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('B_K_LTE_TDD_ACCESS_D_TOP');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBKLTETDDDAYTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('B_K_LTE_TDD_DAY', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('day_id');
            $table->text('province')->comment('省份');
            $table->text('city')->comment('地市');
            $table->decimal('r_access',4,2)->comment('无线接通率');
            $table->decimal('r_lost',4,2)->comment('无线掉线率');
            $table->decimal('r_handover',4,2)->comment('切换成功率');
            $table->decimal('r_highInterfere',4,2)->comment('高干扰小区比例');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('B_K_LTE_TDD_DAY');
    }
}

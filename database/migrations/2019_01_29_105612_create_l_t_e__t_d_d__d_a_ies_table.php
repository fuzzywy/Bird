<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLTETDDDAIesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('B_LTE_TDD_DAY', function (Blueprint $table) {
            $table->increments('id');
            $table->date('day_id');
            $table->string('location',20)->comment('城市');
            $table->decimal('access',6,2)->comment('LTE无线接通率');
            $table->decimal('lost',6,2)->comment('LTE无线掉线率');
            $table->decimal('handover',6,2)->comment('LTE切换成功率');
            $table->decimal('interfererate',6,2)->comment('LTE高干扰比例');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('B_LTE_TDD_DAY');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVOLTETDDDAIesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('B_VOLTE_TDD_DAY', function (Blueprint $table) {
            $table->increments('id');
            $table->date('day_id');
            $table->string('location',20)->comment('城市');
            $table->string('location',20)->comment('城市');
            $table->decimal('access',6,2)->comment('VOLTE无线接通率');
            $table->decimal('lost',6,2)->comment('VOLTE无线掉线率');
            $table->decimal('handover',6,2)->comment('VOLTE切换成功率');
            $table->decimal('srvcc')->comment('SRVCC切换成功率');
            $table->decimal('upackagelost')->comment('上行丢包率');
            $table->decimal('dpackagelost')->comment('下行丢包率');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('B_VOLTE_TDD_DAY');
    }
}

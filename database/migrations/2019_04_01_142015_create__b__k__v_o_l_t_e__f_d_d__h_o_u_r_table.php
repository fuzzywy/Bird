<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBKVOLTEFDDHOURTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('B_K_VOLTE_FDD_HOUR', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('day_id');
            $table->tinyInteger('hour_id');
            $table->text('province')->comment('省份');
            $table->text('city')->comment('城市');
            $table->decimal('r_access',4,2)->comment('VOLTE无线接通率');
            $table->decimal('r_lost',4,2)->comment('VOLTE无线掉线率');
            $table->decimal('r_handover',4,2)->comment('VOLTE切换成功率');
            $table->decimal('r_srvcc',4,2)->comment('SRVCC切换成功率');
            $table->decimal('r_u_packetlost')->comment('上行丢包率');
            $table->decimal('r_d_packetlost')->comment('下行丢包率');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('B_K_VOLTE_FDD_HOUR');
    }
}

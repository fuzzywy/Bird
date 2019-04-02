<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBKGSMDAYTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('B_K_GSM_DAY', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('day_id');
            $table->text('province')->comment('省份');
            $table->text('city')->comment('地市');
            $table->decimal('r_access',4,2)->comment('2G无线接通率');
            $table->decimal('r_lost',4,2)->comment('2G无线掉线率');
            $table->decimal('r_handover',4,2)->comment('2G切换成功率');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('B_K_GSM_DAY');
    }
}

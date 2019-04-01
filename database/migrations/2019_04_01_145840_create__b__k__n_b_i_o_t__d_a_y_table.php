<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBKNBIOTDAYTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('B_K_NBIOT_DAY', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('day_id');
            $table->text('province')->comment('省份');
            $table->text('city')->comment('地市');
            $table->decimal('r_access',4,2)->comment('NBIOT无线接通率');
            $table->decimal('r_lost',4,2)->comment('NBIOT无线掉线率');
            $table->decimal('r_u_floor',4,2)->comment('NBIOT上行底躁（>-110比率）');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('B_K_NBIOT_DAY');
    }
}

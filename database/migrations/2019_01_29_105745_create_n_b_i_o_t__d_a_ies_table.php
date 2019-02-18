<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNBIOTDAIesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('B_NBIOT_DAY', function (Blueprint $table) {
            $table->increments('id');
            $table->date('day_id');
            $table->string('location',20)->comment('城市');
            $table->decimal('access',6,2)->comment('NBIOT无线接通率');
            $table->decimal('interfererate',6,2)->comment('NBIOT高干扰比例');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('B_NBIOT_DAY');
    }
}

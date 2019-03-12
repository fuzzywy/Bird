<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBGSMTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('B_GSM', function (Blueprint $table) {
            $table->increments('id');
            $table->date('day_id');
            $table->string('province',20)->comment('省份');
            $table->string('location',20)->comment('城市');
            $table->decimal('access',6,2)->comment('2G无线接通率');
            $table->decimal('lost',6,2)->comment('2G无线掉线率');
            $table->decimal('handover',6,2)->comment('2G切换成功率');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('B_GSM');
    }
}

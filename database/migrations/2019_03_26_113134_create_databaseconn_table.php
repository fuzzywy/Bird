<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatabaseconnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('databaseconn', function (Blueprint $table) {
            $table->increments('id');
            $table->string('connName', 255);
            $table->string('cityChinese', 255);
            $table->string('host', 255);
            $table->string('port', 255);
            $table->string('dbName', 255);
            $table->string('userName', 255);
            $table->string('password', 255);
            $table->string('subNetwork', 255);
            $table->string('subNetworkFdd', 255);
            $table->string('subNetworkNbiot', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('databaseconn');
    }
}

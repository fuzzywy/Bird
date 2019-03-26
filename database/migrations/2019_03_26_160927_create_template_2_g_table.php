<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplate2GTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_2_g', function (Blueprint $table) {
            $table->increments('id');
            $table->text('templateName');
            $table->string('elementId', 500);
            $table->string('description', 500);
            $table->string('user', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('template_2_g');
    }
}

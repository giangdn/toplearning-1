<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElAppMobileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_app_mobile', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image');
            $table->string('link')->comment('đường dẫn đến trang download app');
            $table->integer('type')->comment('1: android, 2: apple');
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_app_mobile');
    }
}

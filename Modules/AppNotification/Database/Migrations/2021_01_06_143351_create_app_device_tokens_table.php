<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppDeviceTokensTable extends Migration
{
    public function up()
    {
        Schema::create('el_app_device_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->index();
            $table->string('device_model');
            $table->string('version_code');
            $table->string('device_token', 250);
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_app_device_tokens');
    }
}

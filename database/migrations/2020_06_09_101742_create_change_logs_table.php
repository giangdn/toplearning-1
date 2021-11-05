<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangeLogsTable extends Migration
{
    public function up()
    {
        Schema::create('el_change_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('model', 250);
            $table->text('data')->nullable();
            $table->string('type', 6);
            $table->bigInteger('user_id');
            $table->bigInteger('model_id');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_change_logs');
    }
}

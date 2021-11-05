<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOnlineHistoryEditTable extends Migration
{
    public function up()
    {
        Schema::create('el_online_history_edit', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id');
            $table->bigInteger('user_id');
            $table->string('tab_edit');
            $table->string('ip_address');
            $table->integer('type')->comment('1: online, 2:offline');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_online_history_edit');
    }
}

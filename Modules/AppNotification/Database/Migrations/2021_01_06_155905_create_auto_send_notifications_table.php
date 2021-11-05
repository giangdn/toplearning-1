<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutoSendNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('el_auto_send_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('user_ids');
            $table->string('title', 250);
            $table->text('message');
            $table->string('url', 250)->nullable();
            $table->string('image', 250)->nullable();
            $table->text('error')->nullable();
            $table->tinyInteger('status')->default(2)->comment('2: Chưa gửi, 3: đang gửi, 1: đã gửi');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_auto_send_notifications');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElRemoveNotifySendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_remove_notify_send', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('notify_send_id')->nullable()->comment('thông báo bị xoá');
            $table->bigInteger('user_id')->nullable()->comment('người xoá thông báo');
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
        Schema::dropIfExists('el_remove_notify_send');
    }
}

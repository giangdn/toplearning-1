<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElNotifySendObjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_notify_send_object', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('notify_send_id');
            $table->bigInteger('title_id')->nullable();
            $table->bigInteger('unit_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->dateTime('time_send')->nullable();
            $table->integer('status')->default(0);
            $table->bigInteger('send_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_notify_send_object');
    }
}

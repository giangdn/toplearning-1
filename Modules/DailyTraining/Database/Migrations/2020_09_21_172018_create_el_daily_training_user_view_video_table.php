<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElDailyTrainingUserViewVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_daily_training_user_view_video', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('video_id');
            $table->bigInteger('user_id');
            $table->string('device')->nullable();
            $table->dateTime('time_view')->nullable();
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
        Schema::dropIfExists('el_daily_training_user_view_video');
    }
}

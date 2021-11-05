<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElDailyTrainingUserLikeVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_daily_training_user_like_video', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('video_id');
            $table->bigInteger('user_id');
            $table->integer('like')->nullable();
            $table->integer('dislike')->nullable();
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
        Schema::dropIfExists('el_daily_training_user_like_video');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElDailyTrainingUserCommentVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_daily_training_user_comment_video', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('video_id');
            $table->bigInteger('user_id');
            $table->longText('content');
            $table->integer('failed')->default(0);
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
        Schema::dropIfExists('el_daily_training_user_comment_video');
    }
}

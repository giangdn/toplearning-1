<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMdlElPromotionUserPointGetHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_promotion_user_point_get_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('user_type')->default(1);
            $table->integer('point');
            $table->string('type')->nullable();
            $table->integer('course_id')->nullable();
            $table->integer('promotion_course_setting_id')->nullable();
            $table->integer('daily_training')->nullable();
            $table->integer('video_id')->nullable();
            $table->integer('donate_point')->nullable();
            $table->integer('promotion')->nullable();
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
        Schema::dropIfExists('el_promotion_user_point_get_history');
    }
}

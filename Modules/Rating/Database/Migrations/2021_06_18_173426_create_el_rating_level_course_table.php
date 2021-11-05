<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElRatingLevelCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_rating_level_course', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_rating_level_id')->comment('id của offline_rating_level hoặc online_rating_level hoặc course_rating_level');
            $table->bigInteger('course_rating_level_object_id')->default(0)->comment('id course_rating_level_object');
            $table->integer('level');
            $table->bigInteger('user_id')->comment('người làm đánh giá');
            $table->bigInteger('user_type')->default(1);
            $table->bigInteger('course_id');
            $table->integer('course_type')->comment('1: online_course, 2: offline_course, 3: rating_levels');
            $table->bigInteger('rating_user')->nullable()->comment('người được đánh giá');
            $table->bigInteger('user_update')->nullable()->comment('người đánh giá thay');
            $table->integer('send')->default(0);
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
        Schema::dropIfExists('el_rating_level_course');
    }
}

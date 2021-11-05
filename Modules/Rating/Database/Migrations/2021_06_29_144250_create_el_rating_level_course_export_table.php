<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElRatingLevelCourseExportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_rating_level_course_export', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_rating_level_id')->comment('id của offline_rating_level hoặc online_rating_level hoặc course_rating_level');
            $table->integer('level');
            $table->bigInteger('user_id')->comment('người làm đánh giá');
            $table->bigInteger('user_type')->default(1);
            $table->bigInteger('course_id');
            $table->integer('course_type')->comment('1: online_course, 2: offline_course; 3: course_rating_level');
            $table->string('title')->nullable();
            $table->text('content')->nullable();
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
        Schema::dropIfExists('el_rating_level_course_export');
    }
}

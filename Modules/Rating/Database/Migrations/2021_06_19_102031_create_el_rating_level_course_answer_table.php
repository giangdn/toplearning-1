<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElRatingLevelCourseAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_rating_level_course_answer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_question_id');
            $table->bigInteger('answer_id');
            $table->string('answer_code')->nullable();
            $table->string('answer_name')->nullable();
            $table->string('text_answer')->nullable();
            $table->string('check_answer_matrix')->nullable();
            $table->string('answer_matrix')->nullable();
            $table->integer('is_text')->default(0)->comment('Nhập text');
            $table->integer('is_check')->default(0)->comment('Chọn câu trả lời');
            $table->integer('is_row')->default(1)->comment('Đáp án dòng');
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
        Schema::dropIfExists('el_rating_level_course_answer');
    }
}

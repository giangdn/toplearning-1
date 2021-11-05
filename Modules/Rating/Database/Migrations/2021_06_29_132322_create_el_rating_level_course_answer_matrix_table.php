<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElRatingLevelCourseAnswerMatrixTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_rating_level_course_answer_matrix', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_question_id');
            $table->string('answer_code')->nullable();
            $table->integer('answer_row_id')->comment('id user_answer dòng');
            $table->integer('answer_col_id')->comment('id user_answer cột');
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
        Schema::dropIfExists('el_rating_level_course_answer_matrix');
    }
}

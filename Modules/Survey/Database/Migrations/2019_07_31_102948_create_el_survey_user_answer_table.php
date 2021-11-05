<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElSurveyUserAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_survey_user_answer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('survey_user_question_id');
            $table->bigInteger('answer_id');
            $table->string('answer_code')->nullable();
            $table->string('answer_name')->nullable();
            $table->string('text_answer')->nullable();
            $table->string('check_answer_matrix')->nullable();
            $table->string('answer_matrix')->nullable();
            $table->integer('is_check')->default(0)->comment('Check câu trả lời');
            $table->integer('is_text')->default(0)->comment('Nhập text');
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
        Schema::dropIfExists('el_survey_user_answer');
    }
}

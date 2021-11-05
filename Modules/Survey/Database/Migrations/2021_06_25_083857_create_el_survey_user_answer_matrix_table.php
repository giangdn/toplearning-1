<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElSurveyUserAnswerMatrixTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_survey_user_answer_matrix', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('survey_user_question_id');
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
        Schema::dropIfExists('el_survey_user_answer_matrix');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElSurveyTemplate2QuestionAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_survey_template2_question_answer', function (Blueprint $table) {
            $table->integer('id');
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->integer('survey_id');
            $table->bigInteger('question_id');
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
        Schema::dropIfExists('el_survey_template2_question_answer');
    }
}

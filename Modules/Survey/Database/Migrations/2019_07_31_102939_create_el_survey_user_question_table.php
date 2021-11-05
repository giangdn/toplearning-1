<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElSurveyUserQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_survey_user_question', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('survey_user_category_id');
            $table->bigInteger('question_id');
            $table->string('question_code')->nullable();
            $table->string('question_name');
            $table->string('answer_essay')->nullable()->comment('câu tự luận');
            $table->string('type')->comment('choice, essay');
            $table->integer('multiple')->default(0)->comment('1: Chọn nhiều, 0: Chọn 1');
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
        Schema::dropIfExists('el_survey_user_question');
    }
}

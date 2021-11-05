<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMdlElQuizEducatePlanQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_quiz_educate_plan_question', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quiz_id')->index('el_quiz_question_quiz_id_index');
            $table->bigInteger('question_id')->nullable()->index('el_quiz_question_question_id_index');
            $table->bigInteger('qcategory_id')->nullable()->index('el_quiz_question_qcategory_id_index');
            $table->tinyInteger('random')->default(0)->index('el_quiz_question_random_index');
            $table->integer('num_order');
            $table->double('max_score')->default(1);
            $table->bigInteger('qqcategory')->default(0)->index('el_quiz_question_qqcategory_index');
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
        Schema::dropIfExists('el_quiz_educate_plan_question');
    }
}

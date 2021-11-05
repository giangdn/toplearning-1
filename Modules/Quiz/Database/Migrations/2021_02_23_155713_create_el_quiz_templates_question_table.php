<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuizTemplatesQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_quiz_templates_question', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quiz_id')->index();
            $table->bigInteger('question_id')->index()->nullable();
            $table->bigInteger('qcategory_id')->index()->nullable();
            $table->tinyInteger('random')->index()->default(0);
            $table->integer('num_order');
            $table->double('max_score')->default(1);
            $table->bigInteger('qqcategory')->index()->default(0);
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
        Schema::dropIfExists('el_quiz_templates_question');
    }
}

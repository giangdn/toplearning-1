<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuizTemplateQuestionCategoryTable extends Migration
{
    public function up()
    {
        Schema::create('el_quiz_template_question_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('template_id')->index();
            $table->string('name');
            $table->integer('num_order')->index();
            $table->integer('percent_group');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_quiz_template_question_category');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuizQuestionCategoryTable extends Migration
{
    public function up()
    {
        Schema::create('el_quiz_question_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quiz_id')->index();
            $table->string('name');
            $table->integer('num_order')->index();
            $table->integer('percent_group')->default(0)->comment('Phần trăm của 1 đề mục');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_quiz_question_category');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuizTeacherQuestionTable extends Migration
{
    public function up()
    {
        Schema::create('el_quiz_teacher_question', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quiz_id')->index();
            $table->bigInteger('attempt_id')->index();
            $table->bigInteger('teacher_id')->index();
            $table->decimal('grade');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_quiz_teacher_question');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuizTemplateQuestionAnswerTable extends Migration
{
    public function up()
    {
        Schema::create('el_quiz_template_question_answer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('question_id')->index()->comment('id câu hỏi trong el_quiz_template_question');
            $table->longText('title');
            $table->tinyInteger('is_text')->index()->default(0);
            $table->integer('correct_answer')->index()->default(0);
            $table->tinyInteger('selected')->index()->default(0);
            $table->float('percent_answer')->nullable()->comment('phần trăm câu trả lời');
            $table->longText('feedback_answer')->nullable()->comment('Phản hồi cụ thể');
            $table->longText('matching_answer')->nullable()->comment('Đáp án nối câu');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_quiz_template_question_answer');
    }
}

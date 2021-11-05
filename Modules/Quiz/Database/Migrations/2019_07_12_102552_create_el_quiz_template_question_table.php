<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuizTemplateQuestionTable extends Migration
{
    public function up()
    {
        Schema::create('el_quiz_template_question', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('template_id')->index();
            $table->bigInteger('question_id')->index()->comment('id câu hỏi trong el_question');
            $table->integer('qindex')->index()->comment('index câu hỏi');
            $table->text('name');
            $table->string('type', 150)->index()->comment('loại câu hỏi');
            $table->bigInteger('category_id')->index()->nullable();
            $table->bigInteger('qqcategory_id')->index()->default(0);
            $table->double('score_group')->nullable();
            $table->integer('multiple')->default(0)->comment('Cho phép chọn nhiều');
            $table->double('max_score')->default(1)->comment('Điểm tối đa của câu hỏi');
            $table->double('score')->default(0)->comment('Điểm của thí sinh nhận được');
            $table->longText('text_essay')->nullable()->comment('Câu trả lời nếu câu hỏi là tự luận');
            $table->longText('grading_comment')->nullable()->comment('Đánh giá của người chấm thi');
            $table->string('answer')->nullable();
            $table->longText('matching')->nullable();
            $table->string('file_essay')->nullable();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_quiz_template_question');
    }
}

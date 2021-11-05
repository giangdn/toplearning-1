<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuestionAnswerTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('el_question_answer')) {
            Schema::create('el_question_answer', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->longText('title')->nullable();
                $table->bigInteger('question_id')->index();
                $table->tinyInteger('correct_answer')->index()->default(0);
                $table->float('percent_answer')->nullable()->comment('phần trăm câu trả lời');
                $table->longText('feedback_answer')->nullable()->comment('Phản hồi cụ thể');
                $table->longText('matching_answer')->nullable()->comment('Đáp án nối câu');
                $table->string('image_answer')->nullable()->comment('Đáp án hình ảnh');
                $table->longText('fill_in_correct_answer')->nullable()->comment('Đáp án điền từ chính xác');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        /*Schema::dropIfExists('el_question_answer');*/
    }
}

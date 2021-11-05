<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElReportCorrectAnswerRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_report_correct_answer_rate', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quiz_template_id')->comment('đề thi');
            $table->bigInteger('question_id')->comment('câu hỏi');
            $table->string('question_type')->comment('loại câu hỏi');
            $table->integer('num_question_used')->default(0)->comment('Số lần câu hỏi được dùng');
            $table->integer('num_correct_answer')->default(0)->comment('số lần trả lời đúng');
            $table->integer('num_answer')->default(0)->comment('số lần trả lời');
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
        Schema::dropIfExists('el_report_correct_answer_rate');
    }
}

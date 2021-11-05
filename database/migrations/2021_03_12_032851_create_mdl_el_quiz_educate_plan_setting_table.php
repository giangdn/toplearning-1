<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMdlElQuizEducatePlanSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_quiz_educate_plan_setting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quiz_id')->index('el_quiz_setting_quiz_id_index');
            $table->integer('after_test_review_test')->nullable()->comment('Xem lại bài sau khi thi');
            $table->integer('after_test_yes_no')->nullable()->comment('Đúng hay không sau khi thi');
            $table->integer('after_test_score')->nullable()->comment('Xem điểm sau khi thi');
            $table->integer('after_test_specific_feedback')->nullable()->comment('Xem phản hồi chi tiết câu trả lời sau khi thi');
            $table->integer('after_test_general_feedback')->nullable()->comment('Xem phản hồi chung câu hỏi sau khi thi');
            $table->integer('after_test_correct_answer')->nullable()->comment('Xem câu trả lời đúng sau khi thi');
            $table->integer('exam_closed_review_test')->nullable()->comment('Xem lại bài kỳ thi đóng');
            $table->integer('exam_closed_yes_no')->nullable()->comment('Đúng hay không kỳ thi đóng');
            $table->integer('exam_closed_score')->nullable()->comment('Xem điểm sau khi thi');
            $table->integer('exam_closed_specific_feedback')->nullable()->comment('Xem phản hồi chi tiết câu trả lời kỳ thi đóng');
            $table->integer('exam_closed_general_feedback')->nullable()->comment('Xem phản hồi chung câu hỏi kỳ thi đóng');
            $table->integer('exam_closed_correct_answer')->nullable()->comment('Xem câu trả lời đúng kỳ thi đóng');
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
        Schema::dropIfExists('el_quiz_educate_plan_setting');
    }
}

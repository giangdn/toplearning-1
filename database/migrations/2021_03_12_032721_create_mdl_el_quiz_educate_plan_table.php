<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMdlElQuizEducatePlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_quiz_educate_plan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 150)->index('el_quiz_code_index');
            $table->string('name', 191);
            $table->bigInteger('unit_id')->nullable()->index('el_quiz_unit_id_index')->comment('Đơn vị tạo kỳ thi');
            $table->bigInteger('type_id')->nullable()->index('el_quiz_type_id_index')->comment('Danh mục Loại kỳ thi');
            $table->integer('limit_time')->default(60)->comment('Thời gian làm bài: phút');
            $table->integer('view_result')->default(0)->comment('1: được xem kết quả, 0: không được xem kết quả');
            $table->tinyInteger('shuffle_answers')->default(0)->comment('Xáo trộn đáp án');
            $table->tinyInteger('shuffle_question')->default(0)->comment('Xáo trộn câu hỏi');
            $table->tinyInteger('paper_exam')->default(0)->comment('Thi giấy');
            $table->integer('questions_perpage')->default(10)->comment('Số câu hỏi 1 trang');
            $table->double('pass_score')->default(5)->comment('Điểm chuẩn');
            $table->double('max_score')->default(10)->comment('Điểm tối đa');
            $table->string('description', 191)->nullable();
            $table->integer('max_attempts')->default(1)->comment('Số lần làm bài');
            $table->integer('grade_methor')->default(1)->comment('Cách tính điểm');
            $table->integer('is_open')->default(0)->index('el_quiz_is_open_index');
            $table->integer('status')->default(2)->index('el_quiz_status_index')->comment('1: Duyệt, 2: Chưa duyệt, 0:Từ chối');
            $table->bigInteger('course_id')->nullable()->index('el_quiz_course_id_index')->comment('ID khóa học');
            $table->integer('course_type')->nullable()->index('el_quiz_course_type_index')->comment('Loại khóa học');
            $table->integer('quiz_type')->nullable()->index('el_quiz_quiz_type_index')->comment('Loại kỳ thi, 1:offline, 2:tập trung, 3: độc lập');
            $table->string('img', 191)->nullable();
            $table->tinyInteger('webcam_require')->default(0);
            $table->tinyInteger('question_require')->default(0);
            $table->tinyInteger('times_shooting_webcam')->default(0);
            $table->tinyInteger('status_convert')->nullable();
            $table->tinyInteger('times_shooting_question')->default(0);
            $table->bigInteger('created_by')->nullable()->index('el_quiz_created_by_index');
            $table->bigInteger('updated_by')->nullable()->index('el_quiz_updated_by_index');
            $table->integer('unit_by')->nullable()->index('el_quiz_unit_by_index');
            $table->integer('quiz_template_id')->nullable()->index('el_quiz_quiz_template_id_index');
            $table->timestamps();
            $table->integer('quiz_convert_id')->nullable()->default(0);
            $table->integer('suggest_id')->nullable()->default(0);
            $table->integer('approved_by')->nullable();
            $table->dateTime('time_approved')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_quiz_educate_plan');
    }
}

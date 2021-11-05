<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMdlElCourseEducatePlanScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_course_educate_plan_schedule', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id');
            $table->time('start_time');
            $table->time('end_time');
            $table->dateTime('lesson_date');
            $table->bigInteger('teacher_main_id')->comment('Giảng viên chính');
            $table->bigInteger('teach_id')->nullable()->comment('Trợ giảng');
            $table->decimal('cost_teacher_main', 15)->comment('Chi phí giảng viên chính');
            $table->double('cost_teach_type', 8, 2)->nullable()->comment('Chi phí trợ giảng');
            $table->integer('total_lessons');
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
        Schema::dropIfExists('el_course_educate_plan_schedule');
    }
}

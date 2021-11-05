<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElCoursePlanScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_course_plan_schedule', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id');
            $table->bigInteger('course_type');
            $table->time('start_time');
            $table->time('end_time');
            $table->dateTime('lesson_date');
            $table->bigInteger('teacher_main_id')->comment('Giảng viên chính');
            $table->bigInteger('teach_id')->nullable()->comment('Trợ giảng');
            $table->decimal('cost_teacher_main', 15)->comment('Chi phí giảng viên chính');
            $table->float('cost_teach_type')->nullable()->comment('Chi phí trợ giảng');
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
        Schema::dropIfExists('el_course_plan_schedule');
    }
}

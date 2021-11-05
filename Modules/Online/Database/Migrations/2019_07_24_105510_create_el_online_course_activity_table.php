<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOnlineCourseActivityTable extends Migration
{
    public function up()
    {
        Schema::create('el_online_course_activity', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->bigInteger('course_id')->index();
            $table->bigInteger('activity_id')->index();
            $table->bigInteger('subject_id')->index();
            $table->integer('num_order');
            $table->integer('lesson_id');
            $table->tinyInteger('status')->index()->default('0')->comment('0: ẩn, 1: hiện');
            $table->integer('setting_complete_course_activity_id')->nullable()->comment('Hoạt động cần hoàn thành');
            $table->dateTime('setting_start_date')->nullable()->comment('Tới thời gian này mới được truy cập');
            $table->dateTime('setting_end_date')->nullable()->comment('Tới thời gian này không được truy cập nữa');
            $table->integer('setting_score_course_activity_id')->nullable()->comment('Hoạt động cần điểm');
            $table->integer('setting_min_score')->nullable()->comment('Điểm tối thiểu đạt được');
            $table->integer('setting_max_score')->nullable()->comment('Điểm tối đa đạt được');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_online_course_activity');
    }
}

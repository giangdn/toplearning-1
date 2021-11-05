<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOfflineCourseScheduleTeachersTable extends Migration
{
    public function up()
    {
        Schema::create('el_offline_course_schedule_teachers', function (Blueprint $table) {
            $table->bigInteger('schedule_id')->index();
            $table->bigInteger('teacher_id')->index();
            $table->integer('schedule_type')->default(1);
            $table->timestamps();
            $table->primary(['schedule_id', 'teacher_id'], 'mdl_el_offline_course_schedule_teachers_primary');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_offline_course_schedule_teachers');
    }
}

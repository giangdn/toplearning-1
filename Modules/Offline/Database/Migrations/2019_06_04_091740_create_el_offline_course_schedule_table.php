<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOfflineCourseScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_offline_course_schedule', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id')->index();
            $table->integer('lesson_no');
            $table->bigInteger('lesson_date');
            $table->bigInteger('start_time');
            $table->bigInteger('end_time');
            $table->integer('total_lessons');
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');
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
        Schema::dropIfExists('el_offline_course_schedule');
    }
}

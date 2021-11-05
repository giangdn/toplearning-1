<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOfflineScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_offline_schedule', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('schedule_parent_id');
            $table->bigInteger('course_id');
            $table->time('start_time');
            $table->time('end_time');
            $table->dateTime('lesson_date');
            $table->bigInteger('teacher_main_id')->nullable()->comment('Giảng viên chính');
            $table->bigInteger('teach_id')->nullable()->comment('Trợ giảng');
            $table->decimal('cost_teacher_main', 15)->nullable()->comment('Chi phí giảng viên chính');
            $table->float('cost_teach_type')->nullable()->comment('Chi phí trợ giảng');
            $table->integer('total_lessons')->default(1);
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
        Schema::dropIfExists('el_offline_schedule');
    }
}

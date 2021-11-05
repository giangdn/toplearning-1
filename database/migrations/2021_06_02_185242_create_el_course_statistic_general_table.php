<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElCourseStatisticGeneralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_course_statistic_general', function (Blueprint $table) {
            $table->integer('course_held')->nullable()->comment('khóa học đã tổ chức');
            $table->integer('course_not_held')->nullable()->comment('khóa học chưa tổ chức');
            $table->integer('course_pending')->nullable()->comment('khóa học chờ duyệt');
            $table->integer('course_deny')->nullable()->comment('khóa học bị từ chối');
            $table->integer('course_total')->nullable()->comment('Tổng khóa học');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_course_statistic_general');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOnlineCourseActivityHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('el_online_course_activity_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id')->index();
            $table->bigInteger('activity_id')->index();
            $table->bigInteger('course_activity_id')->index();
            $table->bigInteger('user_id')->index();
            $table->bigInteger('user_type')->default(1);
            $table->bigInteger('register_id')->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_online_course_activity_history');
    }
}

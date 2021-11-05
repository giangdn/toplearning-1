<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOnlineCourseActivityCompletionTable extends Migration
{
    public function up()
    {
        Schema::create('el_online_course_activity_completion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->index();
            $table->bigInteger('user_type')->default(1);
            $table->bigInteger('activity_id')->index()->comment('el_online_course_activity');
            $table->bigInteger('course_id')->index();
            $table->bigInteger('status')->default(0);
            $table->unique(['user_id', 'activity_id'], 'online_course_activity_completion_unique');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_online_course_activity_completion');
    }
}

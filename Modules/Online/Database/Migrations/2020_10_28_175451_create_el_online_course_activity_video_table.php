<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOnlineCourseActivityVideoTable extends Migration
{
    public function up()
    {
        Schema::create('el_online_course_activity_video', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id')->index();
            $table->string('path', 150)->index();
            $table->string('extension', 10);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_online_course_activity_video');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOnlineCourseActivityUrlTable extends Migration
{
    public function up()
    {
        Schema::create('el_online_course_activity_url', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id')->index();
            $table->text('url');
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_online_course_activity_url');
    }
}

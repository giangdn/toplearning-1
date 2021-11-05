<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlineCourseActivityBbbsTable extends Migration
{
    public function up()
    {
        Schema::create('el_online_course_activity_bbbs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id')->index();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_online_course_activity_bbbs');
    }
}

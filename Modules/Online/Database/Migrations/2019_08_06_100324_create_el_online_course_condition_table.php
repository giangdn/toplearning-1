<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOnlineCourseConditionTable extends Migration
{
    public function up()
    {
        Schema::create('el_online_course_condition', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id')->unique();
            $table->tinyInteger('rating')->default(0);
            $table->tinyInteger('orderby')->default(0);
            $table->string('activity', 255);
            $table->tinyInteger('grade_methor')->nullable()->comment('cách tính điểm, 1:lần cao nhất, 2:điểm trung bỉnh, 3:lần thi cuối');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_online_course_condition');
    }
}

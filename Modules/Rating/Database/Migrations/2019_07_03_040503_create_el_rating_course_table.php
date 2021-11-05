<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElRatingCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_rating_course', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('template_id');
            $table->bigInteger('user_id');
            $table->bigInteger('user_type')->default(1);
            $table->bigInteger('course_id');
            $table->integer('type')->comment('1: online_course, 2: offline_course');
            $table->integer('send')->default(0);
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
        Schema::dropIfExists('el_rating_course');
    }
}

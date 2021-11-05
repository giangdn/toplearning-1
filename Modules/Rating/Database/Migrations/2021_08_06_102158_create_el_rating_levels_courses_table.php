<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElRatingLevelsCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_rating_levels_courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('rating_levels_id')->comment('id của el_rating_levels');
            $table->integer('course_id')->comment('id khóa onl / off');
            $table->integer('course_type')->comment('1: onl, 2: off');
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
        Schema::dropIfExists('el_rating_levels_courses');
    }
}

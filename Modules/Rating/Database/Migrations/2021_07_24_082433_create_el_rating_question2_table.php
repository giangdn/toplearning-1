<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElRatingQuestion2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_rating_question2', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('course_rating_level_id')->comment('id của offline_rating_level hoặc online_rating_level hoặc course_rating_level');
            $table->integer('course_rating_level_object_id')->default(0)->comment('id course_rating_level_object');
            $table->integer('course_id');
            $table->integer('course_type')->comment('1: online, 2: offline; 3: course_rating_level');
            $table->string('code')->nullable();
            $table->string('name');
            $table->integer('category_id');
            $table->string('type')->comment('multiple_choice, essay');
            $table->integer('multiple')->default(0)->comment('1: Chọn nhiều, 0: Chọn 1');
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
        Schema::dropIfExists('el_rating_question2');
    }
}

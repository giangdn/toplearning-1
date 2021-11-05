<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElRatingQuestionAnswer2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_rating_question_answer2', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('course_rating_level_id')->comment('id của offline_rating_level hoặc online_rating_level hoặc course_rating_level');
            $table->integer('course_rating_level_object_id')->default(0)->comment('id course_rating_level_object');
            $table->integer('course_id');
            $table->integer('course_type')->comment('1: online, 2: offline; 3: course_rating_level');
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->integer('question_id');
            $table->integer('is_text')->default(0)->comment('Nhập text');
            $table->integer('is_row')->default(1)->comment('Đáp án dòng');
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
        Schema::dropIfExists('el_rating_question_answer2');
    }
}

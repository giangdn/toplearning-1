<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMdlElCourseEducatePlanConditionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_course_educate_plan_condition', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id')->unique('el_course_plan_condition_course_id_unique');
            $table->integer('ratio')->nullable()->comment('Tỉ lệ tham gia');
            $table->double('minscore')->nullable()->comment('Điểm tối thiểu');
            $table->integer('survey')->nullable()->comment('Thực hiện bài đánh giá');
            $table->integer('certificate')->nullable()->comment('Chứng chỉ khóa học');
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
        Schema::dropIfExists('el_course_educate_plan_condition');
    }
}

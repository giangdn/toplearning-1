<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElCourseRatingLevelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_course_rating_level', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('rating_levels_id');
            $table->integer('level')->comment('cấp độ đánh giá: 1,2,3,4');
            $table->integer('rating_template_id')->nullable()->comment('id mẫu đánh giá sau khóa học');
            $table->string('rating_name')->comment('Tên đánh giá');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('unit_by')->nullable();
            $table->integer('object_rating')->nullable()->comment('Đối tượng được đánh giá: 1.Lớp học, 2.HV');
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
        Schema::dropIfExists('el_course_rating_level');
    }
}

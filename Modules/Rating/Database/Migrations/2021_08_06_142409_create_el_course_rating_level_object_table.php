<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElCourseRatingLevelObjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_course_rating_level_object', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('rating_levels_id');
            $table->integer('course_rating_level_id');
            $table->integer('rating_template_id')->nullable()->comment('id mẫu đánh giá sau khóa học');
            $table->integer('object_type')->default(1)->comment('Loại đối tượng; 1:HV, 2:TĐV, 3:Đồng nghiệp, 4: Khác');
            $table->integer('time_type')->nullable()->comment('Loại thời gian; 1:khoảng, 2:bắt đầu khóa, 3:kết thúc khóa, 4: hoàn thành khóa');
            $table->integer('num_date')->nullable();
            $table->integer('num_user')->nullable()->comment('SL với loại đối tượng là đồng nghiệp');
            $table->string('user_id')->nullable()->comment('Lưu 1 hoặc nhiều user với loại đối tượng là khác');
            $table->string('rating_user_id')->nullable()->comment('Lưu nv bị đánh giá với loại đối tượng là khác');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->integer('object_view_rating')->default(0)->comment('Đối tượng xem đánh giá; 1: HV, 2: TĐV');
            $table->integer('user_completed')->default(0)->comment('Đánh giá HV khi. 0:Không hoàn thành, 1:Hoàn thành');
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
        Schema::dropIfExists('el_course_rating_level_object');
    }
}

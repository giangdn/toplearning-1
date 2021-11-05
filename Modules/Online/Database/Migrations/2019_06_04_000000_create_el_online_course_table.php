<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOnlineCourseTable extends Migration
{
    public function up()
    {
        Schema::create('el_online_course', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 150)->unique();
            $table->string('name');
            $table->integer('auto')->default(0)->comment('1: tự động duyệt, 0: duyệt tay');
            $table->string('unit_id')->nullable()->comment('Đơn vị tạo khóa học');
            $table->integer('moodlecourseid')->nullable();
            $table->tinyInteger('isopen')->default(0);
            $table->text('tutorial')->nullable();
            $table->text('type_tutorial')->nullable();
            $table->string('image')->nullable();
            $table->string('image_activity')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->bigInteger('created_by')->index()->default(2);
            $table->bigInteger('updated_by')->index()->default(2);
            $table->bigInteger('category_id')->index()->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('training_program_id')->index()->unsigned();
            $table->bigInteger('level_subject_id')->unsigned()->nullable();
            $table->bigInteger('subject_id')->index();
            $table->bigInteger('plan_detail_id')->index()->nullable();
            $table->bigInteger('in_plan')->index()->nullable()->comment('Trong kế hoạch');
            $table->bigInteger('training_form_id')->index()->nullable()->comment('Loại hình đào tạo');
            $table->dateTime('register_deadline')->nullable()->comment('Hạn đăng ký');
            $table->longText('content')->nullable();
            $table->string('document')->nullable();
            $table->string('course_time')->nullable()->comment('Thời lượng');
            $table->string('course_time_unit')->nullable();
            $table->integer('num_lesson')->nullable()->comment('Bài học');
            $table->tinyInteger('status')->default(2);
            $table->bigInteger('views')->default(0);
            $table->integer('action_plan')->default(0);
            $table->integer('plan_app_template')->nullable()->comment('Mẫu Đánh giá hiệu quả đào tạo');
            $table->integer('plan_app_day')->nullable()->comment('Thời hạn đánh giá');
            $table->integer('cert_code')->nullable();
            $table->integer('has_cert')->nullable();
            $table->integer('rating')->nullable()->comment('Đánh giá sau khóa học');
            $table->bigInteger('template_id')->index()->nullable()->comment('Mẫu đánh giá');
            $table->integer('unit_by')->nullable();
            $table->integer('max_grades')->default(0)->nullable();
			$table->integer('min_grades')->default(0)->nullable();
			$table->string('title_join_id')->nullable();
			$table->string('title_recommend_id')->nullable();
			$table->string('training_object_id')->nullable();
			$table->integer('is_limit_time')->default(0)->nullable();
			$table->string('start_timeday')->default('')->nullable();
			$table->string('end_timeday')->default('')->nullable();
            $table->integer('lock_course')->default(0);
            $table->dateTime('rating_end_date')->nullable();
            $table->string('approved_step')->nullable()->comment('step phê duyệt');
            $table->integer('course_action')->default(0)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_online_course');
    }
}

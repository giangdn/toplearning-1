<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOnlineCourseViewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_online_course_view', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('code', 150)->unique();
            $table->string('name');
            $table->integer('auto')->default(0)->comment('1: tự động duyệt, 0: duyệt tay');
            $table->string('unit_id')->nullable()->comment('Mã đơn vị tạo khóa học');
            $table->integer('moodlecourseid')->index()->nullable();
            $table->tinyInteger('isopen')->index()->default(0);
            $table->text('tutorial')->nullable();
            $table->text('type_tutorial')->nullable();
            $table->string('image')->nullable();
            $table->dateTime('start_date')->index()->nullable();
            $table->dateTime('end_date')->index()->nullable();
            $table->integer('created_by')->index()->default(2);
            $table->integer('updated_by')->index()->default(2);
            $table->integer('category_id')->index()->nullable();
            $table->text('description')->nullable();
            $table->integer('training_program_id')->index()->unsigned()->comment('chủ đề');
            $table->string('training_program_code')->nullable()->comment('Chủ đề');
            $table->string('training_program_name')->nullable()->comment('Chủ đề');
            $table->integer('level_subject_id')->unsigned()->nullable();
            $table->integer('subject_id')->index();
            $table->string('subject_code')->nullable()->comment('chuyên đề');
            $table->string('subject_name')->nullable()->comment('chuyên đề');
            $table->integer('plan_detail_id')->index()->nullable();
            $table->integer('in_plan')->nullable()->comment('Trong kế hoạch');
            $table->integer('training_form_id')->index()->nullable()->comment('Hình thức đào tạo');
            $table->string('training_form_name')->nullable()->comment('Hình thức đào tạo');
            $table->dateTime('register_deadline')->nullable()->comment('Hạn đăng ký');
            $table->longText('content')->nullable();
            $table->string('document')->nullable();
            $table->string('course_time')->nullable()->comment('Thời lượng');
            $table->integer('num_lesson')->nullable()->comment('Bài học');
            $table->tinyInteger('status')->index()->default(2);
            $table->integer('views')->default(0);
            $table->integer('action_plan')->index()->default(0)->comment('Đánh giá hiệu quả đào tạo');
            $table->integer('plan_app_template')->index()->nullable()->comment('Mã mẫu Đánh giá hiệu quả đào tạo');
            $table->string('plan_app_template_name')->nullable()->comment('Mẫu Đánh giá hiệu quả đào tạo');
            $table->integer('plan_app_day')->nullable()->comment('Thời hạn đánh giá');
            $table->integer('cert_code')->nullable();
            $table->integer('has_cert')->index()->nullable();
            $table->integer('rating')->index()->nullable()->comment('Đánh giá sau khóa học');
            $table->integer('template_id')->index()->nullable()->comment('mã mẫu đánh giá');
            $table->string('template_name')->nullable()->comment('Mẫu đánh giá');
            $table->integer('unit_by')->index()->nullable();
            $table->integer('max_grades')->default(0)->nullable();
            $table->integer('min_grades')->default(0)->nullable();
            $table->string('title_join_id')->nullable()->comment('mã chức danh tham gia');
            $table->string('title_join_name')->nullable()->comment('chức danh tham gia');
            $table->string('title_recommend_id')->nullable()->comment('Chức danh khuyến khích');
            $table->string('title_recommend_name')->nullable()->comment('Chức danh khuyến khích');
            $table->string('training_object_id')->nullable()->comment('Đối tượng tham gia');
            $table->string('training_object_name')->nullable()->comment('Đối tượng tham gia');
            $table->integer('is_limit_time')->default(0)->nullable()->comment('giới hạn thời gian học');
            $table->string('start_timeday')->default('')->nullable();
            $table->string('end_timeday')->default('')->nullable();
            $table->integer('lock_course')->default(0);
            $table->dateTime('rating_end_date')->nullable();
            $table->tinyInteger('is_roadmap')->default(0)->comment('Khóa học trong tháp đào tạo');
            $table->string('approved_step')->nullable();
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
        Schema::dropIfExists('el_online_course_view');
    }
}

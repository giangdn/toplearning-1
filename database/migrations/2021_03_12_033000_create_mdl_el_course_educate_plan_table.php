<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMdlElCourseEducatePlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_course_educate_plan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 150)->nullable()->unique('el_course_plan_code_unique');
            $table->string('name', 191);
            $table->integer('auto')->default(0)->comment('1: tự động duyệt, 0: duyệt tay');
            $table->string('unit_id', 191)->nullable()->comment('Đơn vị tạo khóa học');
            $table->tinyInteger('isopen')->default(0);
            $table->string('image', 191)->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->bigInteger('created_by')->default(2)->index('el_course_plan_created_by_index');
            $table->bigInteger('updated_by')->default(2)->index('el_course_plan_updated_by_index');
            $table->bigInteger('category_id')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('training_program_id')->index('el_course_plan_training_program_id_index');
            $table->unsignedBigInteger('level_subject_id')->nullable();
            $table->bigInteger('subject_id')->index('el_course_plan_subject_id_index');
            $table->bigInteger('plan_detail_id')->nullable()->index('el_course_plan_plan_detail_id_index');
            $table->bigInteger('in_plan')->nullable()->index('el_course_plan_in_plan_index')->comment('Trong kế hoạch');
            $table->bigInteger('training_form_id')->nullable()->index('el_course_plan_training_form_id_index')->comment('Hình thức đào tạo');
            $table->dateTime('register_deadline')->nullable()->comment('Hạn đăng ký');
            $table->longText('content')->nullable();
            $table->string('document', 191)->nullable();
            $table->string('course_time', 191)->nullable()->comment('Thời lượng');
            $table->integer('num_lesson')->nullable()->comment('Bài học');
            $table->tinyInteger('status')->default(2);
            $table->bigInteger('views')->default(0);
            $table->integer('action_plan')->default(0)->comment('Đánh giá hiệu quả đào tạo');
            $table->integer('plan_app_template')->nullable()->comment('Mẫu Đánh giá hiệu quả đào tạo');
            $table->integer('plan_app_day')->nullable()->comment('Thời hạn đánh giá');
            $table->integer('cert_code')->nullable();
            $table->integer('has_cert')->nullable();
            $table->integer('rating')->nullable()->comment('Đánh giá sau khóa học');
            $table->bigInteger('template_id')->nullable()->index('el_course_plan_template_id_index')->comment('Mẫu đánh giá');
            $table->integer('unit_by')->nullable();
            $table->integer('max_student')->nullable();
            $table->bigInteger('training_location_id')->nullable();
            $table->string('training_unit', 191)->nullable();
            $table->bigInteger('training_area_id')->nullable();
            $table->bigInteger('training_partner_id')->nullable();
            $table->bigInteger('teacher_id')->nullable();
            $table->tinyInteger('commit')->nullable();
            $table->dateTime('commit_date')->nullable();
            $table->double('coefficient')->nullable();
            $table->decimal('cost_class')->nullable();
            $table->bigInteger('quiz_id')->nullable();
            $table->tinyInteger('status_convert')->default(0);
            $table->timestamps();
            $table->integer('approved_by')->nullable()->default(0);
            $table->timestamp('time_approved')->nullable();
            $table->integer('course_convert_id')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_course_educate_plan');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElCoursePlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_course_plan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_type')->default(1)->comment('1: online, 2:offline');
            $table->string('code', 150)->nullable();
            $table->string('name');
            $table->integer('auto')->default(0)->comment('1: tự động duyệt, 0: duyệt tay');
            $table->string('unit_id')->nullable()->comment('Đơn vị tạo khóa học');
            $table->integer('moodlecourseid')->nullable();
            $table->tinyInteger('isopen')->default(0);
            $table->string('image')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->bigInteger('created_by')->index()->default(2);
            $table->bigInteger('updated_by')->index()->default(2);
            $table->bigInteger('category_id')->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('training_program_id')->index()->unsigned();
            $table->bigInteger('level_subject_id')->unsigned()->nullable();
            $table->bigInteger('subject_id')->index();
            $table->bigInteger('plan_detail_id')->index()->nullable();
            $table->bigInteger('in_plan')->index()->nullable()->comment('Trong kế hoạch');
            $table->bigInteger('training_form_id')->index()->nullable()->comment('Hình thức đào tạo');
            $table->dateTime('register_deadline')->nullable()->comment('Hạn đăng ký');
            $table->longText('content')->nullable();
            $table->string('document')->nullable();
            $table->string('course_time')->nullable()->comment('Thời lượng');
            $table->integer('num_lesson')->nullable()->comment('Bài học');
            $table->tinyInteger('status')->default(2);
            $table->bigInteger('views')->default(0);
            $table->integer('action_plan')->default(0)->comment('Đánh giá hiệu quả đào tạo');
            $table->integer('plan_app_template')->nullable()->comment('Mẫu Đánh giá hiệu quả đào tạo');
            $table->integer('plan_app_day')->nullable()->comment('Thời hạn đánh giá');
            $table->integer('cert_code')->nullable();
            $table->integer('has_cert')->nullable();
            $table->integer('rating')->nullable()->comment('Đánh giá sau khóa học');
            $table->bigInteger('template_id')->index()->nullable()->comment('Mẫu đánh giá');
            $table->integer('unit_by')->nullable();
            $table->integer('max_student')->nullable();
            $table->bigInteger('training_location_id')->nullable();
            $table->string('training_unit')->nullable();
            $table->bigInteger('training_area_id')->nullable();
            $table->string('training_partner_id')->nullable();
            $table->bigInteger('teacher_id')->nullable();
            $table->tinyInteger('commit')->nullable();
            $table->dateTime('commit_date')->nullable();
            $table->double('coefficient')->nullable();
            $table->decimal('cost_class')->nullable();
            $table->bigInteger('quiz_id')->nullable();
            $table->tinyInteger('status_convert')->default(0);
            $table->bigInteger('approved_by')->nullable();
            $table->dateTime('time_approved')->nullable();
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
        Schema::dropIfExists('el_course_plan');
    }
}

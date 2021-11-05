<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElTrainingPlanDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_training_plan_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('plan_id')->unsigned();
            $table->bigInteger('training_program_id')->unsigned();
            $table->bigInteger('level_subject_id')->nullable();
            $table->bigInteger('subject_id')->unsigned();
            $table->string('course_type')->default(1)->comment('Hình thức đào tạo: 1:Trực tuyến, 2:Tập trung, 3:Tự học');
            $table->string('training_form_id')->nullable()->comment('Loại hình đào tạo');
            $table->string('training_partner')->nullable()->comment('Đơn vị tổ chức');
            $table->integer('training_partner_type')->default(0)->comment('0:Nội bộ, 1:Bên ngoài');
            $table->string('periods')->default(0)->comment('thời lượng đào tạo');
            $table->integer('quarter1')->default(0);
            $table->integer('quarter2')->default(0);
            $table->integer('quarter3')->default(0);
            $table->integer('quarter4')->default(0);
            $table->string('responsable')->nullable()->comment('Chịu trách nhiệm tổ chức');
            $table->integer('responsable_type')->default(0)->comment('0:Nội bộ, 1:Bên ngoài');
            $table->integer('total_course')->default(0)->comment('Tổng số lớp trong năm');
            $table->integer('total_student')->default(0)->comment('Số lượng học viên');
            $table->text('type_costs')->nullable()->comment('Tất cả loại chi phí');
            $table->integer('total_type_cost')->nullable()->comment('Tổng tiền Tất cả loại chi phí');
            $table->string('note')->nullable();
            $table->integer('created_by')->default(1);
            $table->integer('updated_by')->default(1);
            $table->integer('unit_by')->nullable()->default(1);
            $table->integer('exis_training_CBNV')->nullable()->comment('Nhu cầu đào tạo CBNV hiện hữu');
            $table->integer('recruit_training_CBNV')->nullable()->comment('Nhu cầu đào tạo CBNV tân tuyển');
            $table->string('training_object_id')->nullable()->comment('Đối tượng đào tạo');
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
        Schema::dropIfExists('el_training_plan_detail');
    }
}

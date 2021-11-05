<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElPlanSuggestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_plan_suggest', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('unit_code')->comment('Mã đơn vị');
            $table->string('subject_name')->nullable()->comment('Tên học phần');
            $table->string('title',500)->nullable()->comment('Đối tượng học (chức danh)');
            $table->integer('amount')->nullable()->comment('Số lượng học viên');
            $table->integer('type')->comment('1: nội bộ; 2: bên ngoài');
            $table->bigInteger('training_form')->nullable()->comment('Loại hình');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('address')->nullable();
            $table->float('cost')->nullable();
            $table->string('note',1000)->nullable()->comment('Ghi chú');

            $table->date('intend')->nullable()->comment('Thời gian dự kiến tháng/năm');
            $table->string('purpose',1000)->nullable()->comment('Mục tiêu đào tạo');
            $table->integer('duration')->nullable()->comment('Thời lượng (buổi)');
            $table->string('teacher')->nullable()->comment('Giảng viên');
            $table->string('attach')->nullable()->comment('File đính kèm');
            $table->string('attach_report')->nullable()->comment('File đính kèm báo cáo');
            $table->string('students',1000)->nullable()->comment('Danh sách học viên');
            $table->string('content')->nullable()->comment('Nội dung');
            $table->integer('created_by')->comment('Người đề xuất')->index();
            $table->integer('updated_by')->comment('Người đề xuất')->index();
            $table->integer('approved_by')->nullable()->comment('Người duyệt');
            $table->integer('status')->comment('Trạng thái');
            $table->integer('unit_by')->nullable()->index();
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
        Schema::dropIfExists('el_plan_suggest');
    }
}

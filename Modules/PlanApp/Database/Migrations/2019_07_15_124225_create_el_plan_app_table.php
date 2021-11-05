<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElPlanAppTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_plan_app', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('plan_app_id')->comment('Mã Đánh giá hiệu quả đào tạo');
            $table->bigInteger('user_id')->comment('Mã user');
            $table->bigInteger('course_id')->comment('id khóa học');
            $table->integer('course_type')->comment('Loại khóa học');
            $table->string('suggest_self',4000)->nullable()->comment('Đề xuất học viên');
            $table->string('suggest_manager',4000)->nullable()->comment('Đề xuất của TĐV');
            $table->integer('reality_manager')->nullable()->comment('1 vận dụng thực tế, 2 chưa vận dụng thức tế');
            $table->string('reason_reality_manager',4000)->nullable()->comment('Lý do chưa vận dụng thực tế');
            $table->integer('evaluation_self')->nullable()->comment('Đánh giá của nhân viên');
            $table->string('evaluation_manager',4000)->nullable()->comment('Đánh giá của TĐV');
            $table->dateTime('approved_date')->nullable()->comment('Ngày TĐV duyệt');
            $table->dateTime('evaluation_date')->nullable()->comment('Ngày nhân viên tự đánh giá');
            $table->date('start_date')->nullable()->comment('Ngày bắt đầu đánh giá');
            $table->integer('status')->nullable()->comment('Trạng thái 0 từ chối kế hoạch, 1 lập kế hoạch, 2 đã duyệt kế hoạch, 4 đã đánh giá, 5 đã duyệt đánh giá');
            $table->integer('result')->nullable()->comment('Kết quả');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('unit_by')->nullable();
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
        Schema::dropIfExists('el_plan_app');
    }
}

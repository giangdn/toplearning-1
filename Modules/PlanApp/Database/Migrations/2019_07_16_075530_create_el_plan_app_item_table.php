<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElPlanAppItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_plan_app_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',500)->comment('Tên mục tiêu');
            $table->string('criteria_1',1000)->nullable()->comment('Tiêu chí 1');
            $table->string('criteria_2',1000)->nullable()->comment('Tiêu chí 2');
            $table->string('criteria_3',1000)->nullable()->comment('Tiêu chí 3');
            $table->string('result',1000)->nullable()->comment('kết quả đạt được');
            $table->string('finish',1000)->nullable()->comment('% hoàn thành');
            $table->integer('sort')->nullable()->comment('Thứ tự');
            $table->integer('user_id')->comment('mã user');
            $table->bigInteger('cate_id')->comment('Mã đề mục');
            $table->bigInteger('plan_app_id')->comment('Mã template Đánh giá hiệu quả đào tạo');
            $table->bigInteger('course_id')->comment('mã khóa học');
            $table->integer('course_type')->comment('Loại khóa học');
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
        Schema::dropIfExists('el_plan_app_item');
    }
}

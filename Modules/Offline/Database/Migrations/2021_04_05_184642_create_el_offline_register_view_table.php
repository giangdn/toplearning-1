<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOfflineRegisterViewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_offline_register_view', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->integer('user_id')->index();
            $table->string('code')->nullable();
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->integer('title_id')->index()->nullable();
            $table->string('title_code',100)->nullable();
            $table->string('title_name')->nullable();
            $table->integer('position_id')->index()->nullable();
            $table->string('position_code',100)->nullable();
            $table->string('position_name')->nullable();
            $table->integer('unit_id')->index()->nullable();
            $table->string('unit_code',100)->nullable();
            $table->string('unit_name')->nullable();
            $table->integer('parent_unit_id')->index()->nullable();
            $table->string('parent_unit_code',100)->nullable();
            $table->string('parent_unit_name')->nullable();
            $table->integer('course_id')->index();
            $table->integer('status')->index()->default(2)->nullable()->comment('trạng thái đăng ký');
            $table->string('note')->nullable();
            $table->decimal('score',18,2)->nullable()->comment('Điểm thi');
            $table->integer('result')->index()->nullable()->comment('kết quả');
            $table->dateTime('finish_date')->nullable()->comment('Ngày hoàn thành');

            $table->integer('approved_by_1')->nullable()->comment('approved level 1');
            $table->dateTime('approved_date_1')->nullable()->comment('ngày approved level 1');
            $table->integer('status_level_1')->default(2)->nullable()->comment('trạng thái approved level 1 (2 chưa duyệt / 0 từ chối / 1 đã duyệt)');

            $table->integer('approved_by_2')->nullable()->comment('approved level 2');
            $table->dateTime('approved_date_2')->nullable()->comment('ngày approved level 2');
            $table->integer('status_level_2')->default(2)->nullable()->comment('trạng thái approved level 2 (2 chưa duyệt / 0 từ chối / 1 đã duyệt)');
            $table->tinyInteger('cron_complete')->index()->nullable()->comment('1 đã chạy cron complete, 0 chưa chạy, null không chạy');
            $table->integer('created_by')->index()->nullable();
            $table->integer('updated_by')->index()->nullable();
            $table->integer('unit_by')->index()->nullable();
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
        Schema::dropIfExists('el_offline_register_view');
    }
}

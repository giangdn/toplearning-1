<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElTrainingProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_training_process', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('user_type')->default(1);
            $table->bigInteger('course_id')->nullable();
            $table->string('course_code')->nullable();
            $table->string('course_name')->nullable();
            $table->integer('course_type')->nullable()->comment('1 online, 2: offline');
            $table->integer('subject_id');
            $table->string('subject_code');
            $table->string('subject_name');
            $table->string('titles_code')->nullable();
            $table->string('titles_name')->nullable();
            $table->string('unit_code')->nullable();
            $table->string('unit_name')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->float('mark')->nullable();
            $table->integer('pass')->nullable()->comment('1 đạt, 0: rớt');
            $table->integer('certificate')->nullable();
            $table->dateTime('time_complete')->nullable()->comment('Thời gian hoàn thành khóa học');
            $table->integer('status')->nullable()->comment('0:Từ chối,1: Đã duyệt, null: Chưa duyệt');
            $table->integer('process_type')->nullable()->comment('1: QTDT,2: Hoàn thành khóa học không có trong QTDT,3: Chuyển quá trình đào tạo,4: Gộp CĐ,5: Tách CĐ, 6: import');
            $table->integer('merge_subject_id')->nullable()->comment('id table merge_subject');
            $table->integer('move_id')->nullable()->comment('id table move_training_process');
            $table->string('note')->nullable()->comment('Ghi chú');
            $table->integer('approved_by')->nullable();
            $table->date('approved_date')->nullable();
            $table->tinyInteger('course_old')->nullable()->comment('khóa cũ');
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
        Schema::dropIfExists('el_training_process');
    }
}

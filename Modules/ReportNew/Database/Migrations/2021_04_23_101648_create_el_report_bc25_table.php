<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElReportBc25Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_report_bc25', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('subject_id')->nullable()->comment('Id chuyên đề');
            $table->string('subject_code')->nullable()->comment('Mã chuyên đề');
            $table->string('subject_name')->nullable()->comment('Chuyên đề');

            $table->integer('class_1')->nullable()->comment('Số lớp');
            $table->integer('attend_1')->nullable()->comment('Số lượt tham dự');
            $table->integer('completed_1')->nullable()->comment('Số lượt hoàn thành');

            $table->integer('class_2')->nullable()->comment('Số lớp');
            $table->integer('attend_2')->nullable()->comment('Số lượt tham dự');
            $table->integer('completed_2')->nullable()->comment('Số lượt hoàn thành');

            $table->integer('class_3')->nullable()->comment('Số lớp');
            $table->integer('attend_3')->nullable()->comment('Số lượt tham dự');
            $table->integer('completed_3')->nullable()->comment('Số lượt hoàn thành');

            $table->integer('class_4')->nullable()->comment('Số lớp');
            $table->integer('attend_4')->nullable()->comment('Số lượt tham dự');
            $table->integer('completed_4')->nullable()->comment('Số lượt hoàn thành');

            $table->integer('class_5')->nullable()->comment('Số lớp');
            $table->integer('attend_5')->nullable()->comment('Số lượt tham dự');
            $table->integer('completed_5')->nullable()->comment('Số lượt hoàn thành');

            $table->integer('class_6')->nullable()->comment('Số lớp');
            $table->integer('attend_6')->nullable()->comment('Số lượt tham dự');
            $table->integer('completed_6')->nullable()->comment('Số lượt hoàn thành');

            $table->integer('class_7')->nullable()->comment('Số lớp');
            $table->integer('attend_7')->nullable()->comment('Số lượt tham dự');
            $table->integer('completed_7')->nullable()->comment('Số lượt hoàn thành');

            $table->integer('class_8')->nullable()->comment('Số lớp');
            $table->integer('attend_8')->nullable()->comment('Số lượt tham dự');
            $table->integer('completed_8')->nullable()->comment('Số lượt hoàn thành');

            $table->integer('class_9')->nullable()->comment('Số lớp');
            $table->integer('attend_9')->nullable()->comment('Số lượt tham dự');
            $table->integer('completed_9')->nullable()->comment('Số lượt hoàn thành');

            $table->integer('class_10')->nullable()->comment('Số lớp');
            $table->integer('attend_10')->nullable()->comment('Số lượt tham dự');
            $table->integer('completed_10')->nullable()->comment('Số lượt hoàn thành');

            $table->integer('class_11')->nullable()->comment('Số lớp');
            $table->integer('attend_11')->nullable()->comment('Số lượt tham dự');
            $table->integer('completed_11')->nullable()->comment('Số lượt hoàn thành');

            $table->integer('class_12')->nullable()->comment('Số lớp');
            $table->integer('attend_12')->nullable()->comment('Số lượt tham dự');
            $table->integer('completed_12')->nullable()->comment('Số lượt hoàn thành');

            $table->integer('year')->index();
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
        Schema::dropIfExists('el_report_bc25');
    }
}

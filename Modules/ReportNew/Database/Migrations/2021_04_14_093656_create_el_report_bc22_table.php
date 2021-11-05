<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElReportBc22Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_report_bc22', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('subject_merge_code')->nullable()->comment('mã chuyên đề gộp');
            $table->string('subject_merge_name')->nullable()->comment('Tên chuyên đề gộp');
            $table->string('subject_merges')->nullable()->comment('những chuyên đề cần gộp');

            $table->string('subject_splits')->nullable()->comment('những chuyên đề đã tách');
            $table->string('subject_split_code')->nullable()->comment('mã chuyên đề tách');
            $table->string('subject_split_name')->nullable()->comment('tên chuyên đề tách');
            $table->tinyInteger('type')->comment('1: merge, 2: split');
            $table->dateTime('date_action')->nullable()->comment('Ngày gộp/tách');
            $table->integer('user_id')->index();
            $table->integer('user_type')->default(1);
            $table->string('user_code')->nullable()->comment('Mã nhân viên');
            $table->string('full_name')->nullable()->comment('Họ tên');
            $table->string('email')->nullable()->comment('Email');
            $table->string('phone')->nullable()->comment('phone');
            $table->string('area_code')->nullable()->comment('mã khu vực');
            $table->string('area_name')->nullable()->comment('Tên khu vực');
            $table->string('unit1_code')->nullable()->comment('Mã đơn vị 1');
            $table->string('unit1_name')->nullable()->comment('Tên đơn vị 1');
            $table->string('unit2_code')->nullable()->comment('Tên đơn vị 2');
            $table->string('unit2_name')->nullable()->comment('Tên đơn vị 2');
            $table->string('unit3_code')->nullable()->comment('Tên đơn vị 3');
            $table->string('unit3_name')->nullable()->comment('Tên đơn vị 3');
            $table->string('title_code')->nullable()->comment('Chức danh');
            $table->string('title_name')->nullable()->comment('Tên Chức danh');
            $table->string('position_code')->nullable()->comment('mã Chức vụ');
            $table->string('position_name')->nullable()->comment('Tên Chức vụ');
            $table->string('note')->nullable()->comment('Ghi chú');
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
        Schema::dropIfExists('el_report_bc22');
    }
}

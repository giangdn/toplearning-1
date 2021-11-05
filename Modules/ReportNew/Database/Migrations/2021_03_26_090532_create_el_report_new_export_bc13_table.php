<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElReportNewExportBc13Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_report_new_export_bc13', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('unit_id_1')->nullable();
            $table->string('unit_code_1')->nullable();
            $table->string('unit_name_1')->nullable();
            $table->integer('unit_id_2')->nullable();
            $table->string('unit_code_2')->nullable();
            $table->string('unit_name_2')->nullable();
            $table->integer('unit_id_3')->nullable();
            $table->string('unit_code_3')->nullable();
            $table->string('unit_name_3')->nullable();
            $table->integer('t1')->default(0);
            $table->integer('t2')->default(0);
            $table->integer('t3')->default(0);
            $table->integer('t4')->default(0);
            $table->integer('t5')->default(0);
            $table->integer('t6')->default(0);
            $table->integer('t7')->default(0);
            $table->integer('t8')->default(0);
            $table->integer('t9')->default(0);
            $table->integer('t10')->default(0);
            $table->integer('t11')->default(0);
            $table->integer('t12')->default(0);
            $table->integer('year')->default(0);
            $table->integer('actual_number_participants')->default(0)->comment('Số người có tham gia đào tạo ít nhất 1 khóa của từng đơn vị');
            $table->integer('hits_actual_participation')->default(0)->comment('Số lượt tham gia tất cả khóa của từng đơn vị');
            $table->string('total_teacher_cost')->default(0)->comment('Tổng chi phí giảng viên');
            $table->string('total_organizational_cost')->default(0)->comment('Tổng chi phí tổ chức');
            $table->string('total_academy_cost')->default(0)->comment('Tổng chi phí học viện');
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
        Schema::dropIfExists('el_report_new_export_bc13');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElReportBc15Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_report_bc15', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('user_type')->default(1);
            $table->integer('title_id');
            $table->string('profile_code')->nullable();
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('area')->nullable();
            $table->string('unit1_code')->nullable();
            $table->string('unit1_name')->nullable();
            $table->string('unit2_code')->nullable();
            $table->string('unit2_name')->nullable();
            $table->string('unit3_code')->nullable();
            $table->string('unit3_name')->nullable();
            $table->string('position')->nullable();
            $table->string('title')->nullable();
            $table->date('join_company')->nullable();
            $table->string('status')->nullable();
            $table->integer('status_id')->nullable();
            $table->text('subject')->nullable();
            $table->integer('mark')->nullable()->comment('ghi dấu học viên đã hoàn thành chuyên đề rồi');
            $table->timestamps();
            $table->primary(['user_id','title_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_report_bc15');
    }
}

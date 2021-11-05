<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElTrainingroadmapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_trainingroadmap', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('training_program_id')->nullable();
            $table->bigInteger('title_id')->unsigned();
            $table->bigInteger('subject_id')->unsigned();
            $table->bigInteger('level_subject_id')->nullable();
            $table->string('completion_time')->nullable()->comment('Thời gian hoàn thành khóa học');
            $table->integer('order')->nullable();
            $table->text('content')->nullable()->comment('Mô tả');
            $table->string('training_form')->comment('1: offline, 2: Tập trung');
            $table->bigInteger('created_by')->nullable()->default(2);
            $table->bigInteger('updated_by')->nullable()->default(2);
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
        Schema::dropIfExists('el_trainingroadmap');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElPotentialRoadmapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_potential_roadmap', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('training_program_id')->nullable();
            $table->bigInteger('title_id')->unsigned();
            $table->bigInteger('subject_id')->unsigned();
            $table->integer('completion_time')->nullable()->comment('Thời gian hoàn thành khóa học');
            $table->integer('order')->nullable();
            $table->text('content')->nullable()->comment('Mô tả');
            $table->integer('training_form')->comment('1: Offline, 2: Tập trung');
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
        Schema::dropIfExists('el_potential_roadmap');
    }
}

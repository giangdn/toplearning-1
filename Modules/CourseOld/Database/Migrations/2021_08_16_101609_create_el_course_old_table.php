<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElCourseOldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_course_old', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('course_code')->nullable();
            $table->string('course_name')->nullable();
            $table->string('user_code')->nullable();
            $table->string('full_name')->nullable();
            $table->string('unit')->nullable();
            $table->string('title')->nullable();
            $table->text('data')->nullable();
            $table->tinyInteger('course_type')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('course_id')->nullable();
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
        Schema::dropIfExists('el_course_old');
    }
}

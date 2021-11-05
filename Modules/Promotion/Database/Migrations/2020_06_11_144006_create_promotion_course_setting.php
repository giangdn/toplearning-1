<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionCourseSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_promotion_course_setting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->nullable();
            $table->integer('course_id');
            $table->integer('type')->comment('1 => online ; 2 => offline');
            $table->integer('method')->default(0)->comment('0: Hoàn thành khóa học, 1: mốc điểm, 2: khác');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->float('min_score')->nullable();
            $table->float('max_score')->nullable();
            $table->float('min_percent')->nullable();
            $table->float('max_percent')->nullable();
            $table->integer('point')->nullable();
            $table->boolean('status')->default(0)->comment('0 => disable ; 1 => enable');
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
        Schema::dropIfExists('el_promotion_course_setting');
    }
}

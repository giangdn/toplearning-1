<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElDailyTrainingVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_daily_training_video', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('video');
            $table->string('avatar');
            $table->string('hashtask');
            $table->bigInteger('category_id');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->integer('unit_by')->nullable();
            $table->bigInteger('user_approve')->nullable();
            $table->dateTime('time_approve')->nullable();
            $table->integer('view')->default(0);
            $table->integer('status')->default(1)->comment('0: bị xoá bởi người tạo');
            $table->bigInteger('approve')->default(2)->comment('0: từ chối, 1: duyệt, 2: chờ duyệt');
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
        Schema::dropIfExists('el_daily_training_video');
    }
}

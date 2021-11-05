<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElProfileManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_profile_manager', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->comment('nhân viên');
            $table->bigInteger('user_manager_id')->comment('Người đồng hành hoặc quản lý');
            $table->integer('level');
            $table->dateTime('start_date')->comment('Ngày bắt đâu');
            $table->dateTime('end_date')->nullable();
            $table->integer('approve')->default(2);
            $table->integer('status')->default(1)->comment('1: Bật, 0: Ẩn');
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
        Schema::dropIfExists('el_profile_manager');
    }
}

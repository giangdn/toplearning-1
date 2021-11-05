<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElPointHistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_point_hist', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('name')->comment('Tên hoạt động nhận điểm');
            $table->integer('type')->comment('1 referer, 2 course_referer, 3 course_finish_referer, 4 course_finish, 5 gift_point');
            $table->integer('referer')->nullable()->comment('user_id Người đã giới thiệu');
            $table->integer('point')->comment('Điểm cộng');
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
        Schema::dropIfExists('el_point_hist');
    }
}

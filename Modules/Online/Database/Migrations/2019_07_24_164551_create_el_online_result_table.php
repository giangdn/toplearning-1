<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOnlineResultTable extends Migration
{
    public function up()
    {
        Schema::create('el_online_result', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('register_id')->index();
            $table->bigInteger('user_id')->index();
            $table->bigInteger('user_type')->default(1);
            $table->bigInteger('course_id')->index();
            $table->double('pass_score')->nullable()->comment('Điểm để đạt');
            $table->double('score')->nullable()->comment('Điểm cuối cùng');
            $table->integer('result')->default(-1)->comment('Kết quả: -1: Chưa có KQ, 1: Đạt, 0: Không đạt');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_online_result');
    }
}

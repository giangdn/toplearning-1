<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOnlineRegisterTable extends Migration
{
    public function up()
    {
        Schema::create('el_online_register', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->index();
            $table->bigInteger('user_type')->default(1);
            $table->bigInteger('course_id')->index();
            $table->integer('status')->index()->default(2)->comment('1: Đã duyệt, 2: Chưa duyệt, 0: Từ chối');
            $table->string('note')->nullable();
            $table->bigInteger('created_by')->index()->default(2);
            $table->bigInteger('updated_by')->index()->default(2);
            $table->integer('unit_by')->index()->nullable();
            $table->tinyInteger('cron_complete')->index()->nullable()->comment('1 đã chạy cron complete, 0 chưa chạy, null không chạy');
            $table->string('approved_step')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_online_register');
    }
}

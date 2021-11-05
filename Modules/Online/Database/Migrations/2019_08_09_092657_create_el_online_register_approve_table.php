<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOnlineRegisterApproveTable extends Migration
{
    public function up()
    {
        Schema::create('el_online_register_approve', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('register_id');
            $table->bigInteger('course_id');
            $table->bigInteger('user_id');
            $table->bigInteger('user_type')->default(1);
            $table->bigInteger('approve_by')->nullable();
            $table->string('approved_step')->nullable();
            $table->tinyInteger('status')->nullable()->comment('null chưa duyệt / 0 từ chối / 1 đã duyệt');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_online_register_approve');
    }
}

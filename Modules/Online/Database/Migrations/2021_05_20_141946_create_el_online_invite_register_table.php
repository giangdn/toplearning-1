<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOnlineInviteRegisterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_online_invite_register', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('course_id')->comment('khóa học được mời ghi danh');
            $table->integer('unit_by')->comment('đơn vị của khóa học được mời ghi danh');
            $table->integer('user_id')->comment('nhân viên được mời');
            $table->integer('role_id')->comment('vai trò người được mời');
            $table->integer('num_register')->comment('sl nhân viên được phép khi danh trong khóa học');
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
        Schema::dropIfExists('el_online_invite_register');
    }
}

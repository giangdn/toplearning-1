<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElProfileTakeLeaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_profile_take_leave', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unique();
            $table->string('full_name')->comment('Họ Tên nhân viên');
            $table->string('absent_code')->nullable()->comment('mã lý do. từ el_adsent');
            $table->string('absent_name')->nullable()->comment('tên lý do. không có trong el_adsent');;
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
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
        Schema::dropIfExists('el_profile_take_leave');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOfflineAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_offline_attendance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('register_id');
            $table->bigInteger('schedule_id');
            $table->bigInteger('course_id');
            $table->bigInteger('user_id')->nullable();
            $table->integer('percent')->nullable()->comment('Phần trăm tham gia');
            $table->string('note')->nullable();
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('el_offline_attendance');
    }
}

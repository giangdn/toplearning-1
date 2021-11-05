<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElRefererRegisterCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_referer_register_course', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id');
            $table->integer('type')->comment('1 online, 2 offline');
            $table->bigInteger('user_id');
            $table->bigInteger('referer');
            $table->tinyInteger('state')->comment('1 đã cập nhật tích lũy điểm');
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
        Schema::dropIfExists('el_referer_register_course');
    }
}

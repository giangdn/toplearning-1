<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElLogViewCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_log_view_course', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id');
            $table->string('course_code',50)->index();
            $table->tinyInteger('course_type');
            $table->string('course_name',256);
            $table->bigInteger('user_id')->index();
            $table->string('user_code')->nullable();
            $table->string('user_name');
            $table->string('session_id',150);
            $table->string('ip_address',45)->nullable();
            $table->string('user_agent',255)->nullable();
            $table->dateTime('last_access')->nullable();
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
        Schema::dropIfExists('el_log_view_course');
    }
}

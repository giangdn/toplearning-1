<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOnlineCourseAskAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_online_course_ask_answer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('course_id');
            $table->integer('user_id_ask');
            $table->integer('user_type_ask')->default(1);
            $table->string('ask',255);
            $table->integer('user_id_answer')->nullable();
            $table->integer('user_type_answer')->default(1);
            $table->string('answer',255)->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('el_online_course_ask_answer');
    }
}

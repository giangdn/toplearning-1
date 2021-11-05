<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElUserCompletedSubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_user_completed_subject', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->index();
            $table->integer('subject_id')->index();
            $table->integer('course_id');
            $table->integer('course_type');
            $table->dateTime('date_completed')->nullable()->comment('ngày hoàn thành');
            $table->string('process_type')->nullable()->comment('E: elearning, O: offline, G: gộp chuyên đề, T: tách chuyên đề, D: duyệt hoàn thành');
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
        Schema::dropIfExists('el_user_completed_subject');
    }
}

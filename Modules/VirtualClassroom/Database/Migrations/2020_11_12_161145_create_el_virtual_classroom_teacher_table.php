<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElVirtualClassroomTeacherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_virtual_classroom_teacher', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('virtual_classroom_id');
            $table->bigInteger('teacher_id');
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
        Schema::dropIfExists('el_virtual_classroom_teacher');
    }
}

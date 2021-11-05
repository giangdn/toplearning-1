<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuizUserAttemptTemplateTable extends Migration
{
    public function up()
    {
        Schema::create('el_quiz_user_attempt_template', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quiz_id')->index();
            $table->bigInteger('part_id')->index();
            $table->bigInteger('user_id')->index();
            $table->bigInteger('user_type')->index();
            $table->string('template_id',500)->comment('bộ đề đã thi');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_quiz_user_attempt_template');
    }
}

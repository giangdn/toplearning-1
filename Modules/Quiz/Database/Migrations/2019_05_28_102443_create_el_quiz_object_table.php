<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuizObjectTable extends Migration
{
    public function up()
    {
        Schema::create('el_quiz_object', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order');
            $table->integer('type')->index();
            $table->bigInteger('course_id')->index();
            $table->longText('corporate_id');
            $table->string('major');
            $table->string('corporate_type');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_quiz_object');
    }
}

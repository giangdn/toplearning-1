<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingActionScoresTable extends Migration
{
    public function up()
    {
        Schema::create('el_training_action_scores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('training_action_id')->index();
            $table->tinyInteger('type')->default(1)->comment('1: teacher, 2: student');
            $table->bigInteger('from');
            $table->bigInteger('to');
            $table->bigInteger('score');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_training_action_scores');
    }
}

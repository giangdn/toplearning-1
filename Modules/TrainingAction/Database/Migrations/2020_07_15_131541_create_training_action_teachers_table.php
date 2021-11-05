<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingActionTeachersTable extends Migration
{
    public function up()
    {
        Schema::create('el_training_action_teachers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('training_action_id')->index();
            $table->bigInteger('user_id')->index();
            $table->tinyInteger('status')->default(2)->comment('1: duyệt, 2: chưa duyệt, 0: từ chối');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_training_action_teachers');
    }
}

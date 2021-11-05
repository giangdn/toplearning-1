<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingActionRegistersTable extends Migration
{
    public function up()
    {
        Schema::create('el_training_action_registers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('training_action_id')->index();
            $table->bigInteger('user_id')->index();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_training_action_registers');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingActionRolesTable extends Migration
{
    public function up()
    {
        Schema::create('el_training_action_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 50)->unique();
            $table->string('name', 200);
            $table->string('name_en', 200)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_training_action_roles');
    }
}

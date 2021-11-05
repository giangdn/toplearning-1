<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingActionTable extends Migration
{
    public function up()
    {
        Schema::create('el_training_action', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 150)->unique();
            $table->string('name', 250);
            $table->string('name_en', 250)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_training_action');
    }
}

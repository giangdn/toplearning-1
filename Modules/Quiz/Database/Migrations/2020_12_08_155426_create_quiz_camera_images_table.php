<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizCameraImagesTable extends Migration
{
    public function up()
    {
        Schema::create('el_quiz_camera_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->index();
            $table->tinyInteger('user_type')->index();
            $table->bigInteger('attempt_id')->index();
            $table->string('path', 150)->index();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_quiz_camera_images');
    }
}

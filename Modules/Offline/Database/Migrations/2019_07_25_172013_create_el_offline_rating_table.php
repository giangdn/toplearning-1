<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOfflineRatingTable extends Migration
{
    public function up()
    {
        Schema::create('el_offline_rating', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id');
            $table->bigInteger('user_id');
            $table->integer('num_star');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_offline_rating');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOnlineRatingTable extends Migration
{
    public function up()
    {
        Schema::create('el_online_rating', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id');
            $table->bigInteger('user_id');
            $table->bigInteger('user_type')->default(1);
            $table->integer('num_star');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_online_rating');
    }
}

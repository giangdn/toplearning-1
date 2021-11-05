<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOnlineObjectTable extends Migration
{
    public function up()
    {
        Schema::create('el_online_object', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id');
            $table->bigInteger('title_id')->nullable();
            $table->bigInteger('unit_id')->nullable();
            $table->bigInteger('area1')->nullable();
            $table->bigInteger('area2')->nullable();
            $table->bigInteger('area3')->nullable();
            $table->bigInteger('area4')->nullable();
            $table->integer('unit_level')->nullable();
            $table->integer('type')->default(1);
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_online_object');
    }
}

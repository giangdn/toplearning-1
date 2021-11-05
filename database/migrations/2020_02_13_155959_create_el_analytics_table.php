<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElAnalyticsTable extends Migration
{
    public function up()
    {
        Schema::create('el_analytics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->index();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->string('ip_address', 150)->nullable();
            $table->date('day')->index();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_analytics');
    }
}

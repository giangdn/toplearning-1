<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElVisitsTable extends Migration
{
    public function up()
    {
        Schema::create('el_visits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('method')->nullable();
            $table->mediumText('url')->nullable();
            $table->mediumText('referer')->nullable();
            $table->text('useragent')->nullable();
            $table->text('device')->nullable();
            $table->text('device_type')->nullable();
            $table->text('device_cate')->nullable();
            $table->text('platform')->nullable();
            $table->text('browser')->nullable();
            $table->ipAddress('ip')->nullable();
            $table->bigInteger('visitor_id')->index();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_visits');
    }
}

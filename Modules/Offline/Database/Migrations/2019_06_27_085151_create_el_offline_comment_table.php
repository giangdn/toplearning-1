<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOfflineCommentTable extends Migration
{
    public function up()
    {
        Schema::create('el_offline_comment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id');
            $table->bigInteger('user_id');
            $table->string('content');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_offline_comment');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuizTemplateTable extends Migration
{
    public function up()
    {
        Schema::create('el_quiz_template', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quiz_id')->index();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_quiz_template');
    }
}

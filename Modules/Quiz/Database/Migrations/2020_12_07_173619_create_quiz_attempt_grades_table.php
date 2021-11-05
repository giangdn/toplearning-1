<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizAttemptGradesTable extends Migration
{
    public function up()
    {
        Schema::create('el_quiz_attempt_grades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('attempt_id')->unique();
            $table->tinyInteger('status')->index()->default(2);
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_quiz_attempt_grades');
    }
}

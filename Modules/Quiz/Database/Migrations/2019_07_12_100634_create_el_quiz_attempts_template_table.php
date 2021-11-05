<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuizAttemptsTemplateTable extends Migration
{
    public function up()
    {
        Schema::create('el_quiz_attempts_template', function (Blueprint $table) {
            $table->bigInteger('attempt_id')->index();
            $table->bigInteger('template_id')->index();
            $table->primary(['attempt_id', 'template_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_quiz_attempts_template');
    }
}

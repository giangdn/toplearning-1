<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuizTemplatesRankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_quiz_templates_rank', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quiz_id')->index();
            $table->string('rank');
            $table->double('score_min');
            $table->double('score_max');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_quiz_templates_rank');
    }
}

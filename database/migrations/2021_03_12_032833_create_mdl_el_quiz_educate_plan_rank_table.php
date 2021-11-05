<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMdlElQuizEducatePlanRankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_quiz_educate_plan_rank', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quiz_id')->index('el_quiz_rank_quiz_id_index');
            $table->string('rank', 191);
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
        Schema::dropIfExists('el_quiz_educate_plan_rank');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElProposedQuestionAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_proposed_question_answer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('question_id');
            $table->string('title');
            $table->tinyInteger('is_text')->default(0);
            $table->tinyInteger('correct_answer')->default(0);
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
        Schema::dropIfExists('el_proposed_question_answer');
    }
}

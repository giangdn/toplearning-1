<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuizUpdateAttemptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_quiz_update_attempt', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('attempt_id')->index();
            $table->bigInteger('quiz_id')->index();
            $table->bigInteger('part_id')->index();
            $table->bigInteger('user_id')->index();
            $table->tinyInteger('type')->index()->default(1)->comment('1: Người thi trong, 2: Người thi ngoài');
            $table->tinyInteger('status')->default(2);
            $table->longText('categories')->nullable();
            $table->longText('questions')->nullable();
            $table->float('score')->nullable();
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
        Schema::dropIfExists('el_quiz_update_attempt');
    }
}

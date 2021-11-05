<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuizErrorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_quiz_error', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('attempt_id')->index();
            $table->bigInteger('quiz_id')->index();
            $table->bigInteger('part_id')->index();
            $table->bigInteger('user_id')->index();
            $table->bigInteger('type')->index()->default(1)->comment('1: Người thi trong, 2: Người thi ngoài');
            $table->integer('attempt')->comment('Số lần thử');
            $table->string('note')->nullable();
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
        Schema::dropIfExists('el_quiz_error');
    }
}

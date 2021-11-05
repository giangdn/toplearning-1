<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuizDataOldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_quiz_data_old', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_code')->nullable();
            $table->string('user_name')->nullable();
            $table->string('title')->nullable();
            $table->string('unit')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('quiz_code')->nullable();
            $table->string('quiz_name')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->string('score_essay')->nullable();
            $table->string('score_multiple_choice')->nullable();
            $table->string('result')->nullable();
            $table->string('area')->nullable();
            $table->string('department')->nullable();
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
        Schema::dropIfExists('el_quiz_data_old');
    }
}

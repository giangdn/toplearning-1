<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElQuizStatisticTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_quiz_statistic', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('t1')->nullable()->default(0);
            $table->integer('t2')->nullable()->default(0);
            $table->integer('t3')->nullable()->default(0);
            $table->integer('t4')->nullable()->default(0);
            $table->integer('t5')->nullable()->default(0);
            $table->integer('t6')->nullable()->default(0);
            $table->integer('t7')->nullable()->default(0);
            $table->integer('t8')->nullable()->default(0);
            $table->integer('t9')->nullable()->default(0);
            $table->integer('t10')->nullable()->default(0);
            $table->integer('t11')->nullable()->default(0);
            $table->integer('t12')->nullable()->default(0);
            $table->integer('year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_quiz_statistic');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElVisitsStatisticTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_visits_statistic', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('value')->nullable();
            $table->string('type');
            $table->string('year')->nullable();
//            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_visits_statistic');
    }
}

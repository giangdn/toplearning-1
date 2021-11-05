<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElSurveyObjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_survey_object', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('survey_id');
            $table->bigInteger('title_id')->nullable();
            $table->bigInteger('unit_id')->nullable();
            $table->bigInteger('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_survey_object');
    }
}

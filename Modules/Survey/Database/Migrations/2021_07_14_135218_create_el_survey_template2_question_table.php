<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElSurveyTemplate2QuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_survey_template2_question', function (Blueprint $table) {
            $table->integer('id');
            $table->string('code')->nullable();
            $table->string('name');
            $table->integer('survey_id');
            $table->bigInteger('category_id');
            $table->string('type')->nullable()->comment('multiple_choice, essay');
            $table->integer('multiple')->default(0)->comment('1: Chọn nhiều, 0: Chọn 1');
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
        Schema::dropIfExists('el_survey_template2_question');
    }
}

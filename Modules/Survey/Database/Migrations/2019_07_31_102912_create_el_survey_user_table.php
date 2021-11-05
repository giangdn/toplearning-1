<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElSurveyUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_survey_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('template_id');
            $table->bigInteger('user_id');
            $table->bigInteger('survey_id');
            $table->text('more_suggestions')->comment('Đề xuất khác');
            $table->integer('send')->default(0);
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
        Schema::dropIfExists('el_survey_user');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuizSettingAlertTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_quiz_setting_alert', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('quiz_id')->nullable();
            $table->integer('from_time')->default(0);
            $table->integer('to_time')->default(0);
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
        Schema::dropIfExists('el_quiz_setting_alert');
    }
}

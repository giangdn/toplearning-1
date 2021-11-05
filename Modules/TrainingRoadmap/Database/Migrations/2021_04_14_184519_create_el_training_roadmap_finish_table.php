<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElTrainingRoadmapFinishTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_training_roadmap_finish', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('title_id')->index()->nullable();
            $table->integer('level_subject_id')->index()->nullable()->comment('mãng nghiệp vụ');
            $table->integer('user_finish')->default(0)->nullable()->comment('số người hoàn thành');
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
        Schema::dropIfExists('el_training_roadmap_finish');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElTrainingByTitleDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_training_by_title_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('training_title_id');
            $table->integer('title_id');
            $table->integer('training_title_category_id');
            $table->integer('subject_id');
            $table->string('subject_code');
            $table->string('subject_name');
            $table->integer('num_date');
            $table->string('num_time');
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
        Schema::dropIfExists('el_training_by_title_detail');
    }
}

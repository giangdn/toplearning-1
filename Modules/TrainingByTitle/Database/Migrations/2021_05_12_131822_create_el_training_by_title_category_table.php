<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElTrainingByTitleCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_training_by_title_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('training_title_id');
            $table->integer('title_id');
            $table->string('name');
            $table->integer('num_date_category');
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
        Schema::dropIfExists('el_training_by_title_category');
    }
}

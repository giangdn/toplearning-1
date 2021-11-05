<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOfflineRatingLevelObjectColleagueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_offline_rating_level_object_colleague', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('offline_rating_level_id');
            $table->integer('user_id');
            $table->integer('rating_user_id');
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
        Schema::dropIfExists('el_offline_rating_level_object_colleague');
    }
}

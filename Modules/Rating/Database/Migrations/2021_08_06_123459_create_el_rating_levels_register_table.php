<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElRatingLevelsRegisterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_rating_levels_register', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('rating_levels_id')->comment('id của el_rating_levels');
            $table->integer('user_id');
            $table->integer('unit_id')->nullable();
            $table->string('unit_code')->nullable();
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
        Schema::dropIfExists('el_rating_levels_register');
    }
}

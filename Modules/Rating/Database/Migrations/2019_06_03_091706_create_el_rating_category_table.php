<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElRatingCategoryTable extends Migration
{
    public function up()
    {
        Schema::create('el_rating_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('template_id');
            $table->longText('name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_rating_category');
    }
}

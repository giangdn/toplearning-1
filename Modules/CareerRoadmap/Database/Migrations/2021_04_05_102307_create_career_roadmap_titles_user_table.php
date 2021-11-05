<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCareerRoadmapTitlesUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('career_roadmap_titles_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('career_roadmap_user_id')->index();
            $table->bigInteger('title_id')->index();
            $table->bigInteger('parent_id')->nullable();
            $table->integer('level')->default(0);
            $table->string('seniority')->default(0);
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
        Schema::dropIfExists('career_roadmap_titles_user');
    }
}

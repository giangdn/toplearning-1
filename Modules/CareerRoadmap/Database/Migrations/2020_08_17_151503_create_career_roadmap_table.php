<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCareerRoadmapTable extends Migration
{
    public function up()
    {
        Schema::create('career_roadmap', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 500);
            $table->tinyInteger('primary')->default(0);
            $table->bigInteger('title_id')->index();
            $table->integer('created_by')->nullable()->default(2)->index();
            $table->integer('updated_by')->nullable()->default(2)->index();
            $table->integer('unit_by')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('career_roadmap');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElCapabilitiesTitleSubjectTable extends Migration
{
    public function up()
    {
        Schema::create('el_capabilities_title_subject', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('capabilities_title_id');
            $table->bigInteger('subject_id');
            $table->integer('level');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_capabilities_title_subject');
    }
}

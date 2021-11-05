<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElArmorialEmulationProgramTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_armorial_emulation_program', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('images');
            $table->string('name');
            $table->string('code');
            $table->string('description');
            $table->integer('min_score');
            $table->integer('max_score');
            $table->integer('emulation_id');
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
        Schema::dropIfExists('el_armorial_emulation_program');
    }
}

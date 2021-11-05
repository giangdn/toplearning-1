<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElCapabilitiesGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_capabilities_group', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('basic_knowledge')->nullable();
            $table->string('medium_knowledge')->nullable();
            $table->string('advanced_knowledge')->nullable();
            $table->string('profession_knowledge')->nullable();

            $table->string('basic_skills')->nullable();
            $table->string('medium_skills')->nullable();
            $table->string('advanced_skills')->nullable();
            $table->string('profession_skills')->nullable();

            $table->string('basic_expression')->nullable();
            $table->string('medium_expression')->nullable();
            $table->string('advanced_expression')->nullable();
            $table->string('profession_expression')->nullable();
            
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
        Schema::dropIfExists('el_capabilities_group');
    }
}

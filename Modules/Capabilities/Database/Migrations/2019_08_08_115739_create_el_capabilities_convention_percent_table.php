<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElCapabilitiesConventionPercentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_capabilities_convention_percent', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('percent_id')->comment('el_capabilities_group_percent id');
            $table->string('name');
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
        Schema::dropIfExists('el_capabilities_convention_percent');
    }
}

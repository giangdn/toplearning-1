<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElCapabilitiesGroupPercentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_capabilities_group_percent', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('to_percent')->nullable()->comment('Phần trăm đến');
            $table->float('from_percent')->comment('Phần trăm từ');
            $table->string('percent_group')->comment('Thuộc nhóm');
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
        Schema::dropIfExists('el_capabilities_group_percent');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElCapabilitiesResultTable extends Migration
{
    public function up()
    {
        Schema::create('el_capabilities_result', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('status')->default(0);
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_capabilities_result');
    }
}

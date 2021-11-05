<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElModelApprovedTable extends Migration
{
    /**
     * Run the migrations.x
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_model_approved', function (Blueprint $table) {
            $table->string('model',100)->primary();
            $table->string('name',500);
            $table->integer('status')->default(1)->comment('1: active, 0: unactive');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_model_approved');
    }
}

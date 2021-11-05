<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElWorkingProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_working_process', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_code',50)->index()->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('title_code',50)->index()->nullable();
            $table->string('title_name',256)->nullable();
            $table->string('unit_code',50)->index()->nullable();
            $table->string('unit_name')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->longText('note')->nullable();
            $table->tinyInteger('api')->nullable()->comment('1 api');
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
        Schema::dropIfExists('el_working_process');
    }
}

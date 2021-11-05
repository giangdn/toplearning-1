<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElHasChangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_has_change', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('table_name',50)->index()->comment('tên table');
            $table->bigInteger('record_id')->index()->comment('id table');
            $table->integer('type')->index()->comment('1: update, 2: delete');
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
        Schema::dropIfExists('el_has_change');
    }
}

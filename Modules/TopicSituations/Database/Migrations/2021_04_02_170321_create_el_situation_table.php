<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElSituationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_situation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('topic_id');
            $table->string('code');
            $table->string('name');
            $table->text('description');
            $table->integer('view')->default(0);
            $table->integer('like')->default(0);
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
        Schema::dropIfExists('el_situation');
    }
}

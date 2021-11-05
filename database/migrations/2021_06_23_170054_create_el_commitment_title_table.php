<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElCommitmentTitleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_commitment_title', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('commitment_id')->nullable();
            $table->integer('commit_group_id')->nullable();
            $table->integer('title_id');
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
        Schema::dropIfExists('el_commitment_title');
    }
}

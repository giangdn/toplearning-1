<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElCommitmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_commitment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('min_cost');
            $table->integer('max_cost')->nullable();
            $table->integer('month');
            $table->integer('created_by')->nullable()->index();
            $table->integer('updated_by')->nullable()->index();
            $table->integer('unit_by')->nullable()->index();
            $table->integer('training_type_id')->nullable()->index();
            $table->integer('group_id')->nullable()->comment('Nhóm');
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
        Schema::dropIfExists('el_commitment');
    }
}

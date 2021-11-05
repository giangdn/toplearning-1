<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElTrainingFormTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('el_training_form')) {
            Schema::create('el_training_form', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('code', 150)->unique();
                $table->string('name');
                $table->integer('training_type_id');
                $table->integer('created_by')->nullable()->index();
                $table->integer('updated_by')->nullable()->index();
                $table->integer('unit_by')->nullable()->index();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_training_form');
    }
}

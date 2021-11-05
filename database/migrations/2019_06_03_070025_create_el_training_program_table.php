<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElTrainingProgramTable extends Migration
{
    public function up()
    {
        //if (!Schema::hasTable('el_training_program')) {
            Schema::create('el_training_program', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('code', 150)->unique();
                $table->string('name');
                $table->integer('status')->default(1);
                $table->integer('created_by')->nullable()->index();
                $table->integer('updated_by')->nullable()->index();
                $table->integer('unit_by')->nullable()->index();
                $table->timestamps();
            });
        //}
    }

    public function down()
    {
        Schema::dropIfExists('el_training_program');
    }
}

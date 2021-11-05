<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElCostLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('el_cost_lessons')) {
            Schema::create('el_cost_lessons', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->integer('cost');
                $table->integer('status')->default(1);
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
        Schema::dropIfExists('el_cost_lessons');
    }
}

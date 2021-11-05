<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElDashboardUnitByQuizTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_dashboard_unit_by_quiz', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('unit_id')->nullable();
            $table->string('unit_name')->nullable();
            $table->integer('area_id')->nullable();
            $table->integer('total')->nullable();
            $table->integer('quiz_type')->nullable();
            $table->string('quiz_type_name')->nullable();
            $table->integer('num_user')->default(0);
            $table->integer('num_quiz_part')->default(0);
            $table->string('month');
            $table->string('year');
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
        Schema::dropIfExists('el_dashboard_unit_by_quiz');
    }
}

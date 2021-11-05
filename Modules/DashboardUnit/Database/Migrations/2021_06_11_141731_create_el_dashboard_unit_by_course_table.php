<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElDashboardUnitByCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_dashboard_unit_by_course', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('unit_id')->nullable();
            $table->string('unit_name')->nullable();
            $table->integer('area_id')->nullable();
            $table->integer('total')->nullable();
            $table->string('training_form_id')->nullable();
            $table->string('training_form_name')->nullable();
            $table->integer('num_user')->default(0);
            $table->integer('num_course')->default(0);
            $table->integer('course_employee')->nullable();
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
        Schema::dropIfExists('el_dashboard_unit_by_course');
    }
}

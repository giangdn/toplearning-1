<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElReportNewExportBc26Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_report_new_export_bc26', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('training_plan_id');
            $table->integer('subject_id');
            $table->integer('course_action_1')->default(0);
            $table->integer('course_action_2')->default(0);
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
        Schema::dropIfExists('el_report_new_export_bc26');
    }
}

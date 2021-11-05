<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElTrainingPlanDetailTypeCostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_training_plan_detail_type_cost', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('training_plan_detail_id');
            $table->integer('status')->default(1);
            $table->integer('cost_id');
            $table->integer('training_plan_id');
            $table->integer('created_by')->default(1);
            $table->integer('updated_by')->default(1);
            $table->integer('unit_by')->nullable()->default(1);
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
        Schema::dropIfExists('el_training_plan_detail_type_cost');
    }
}

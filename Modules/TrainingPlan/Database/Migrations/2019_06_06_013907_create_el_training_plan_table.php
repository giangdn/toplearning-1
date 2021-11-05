<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElTrainingPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_training_plan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 150)->unique();
            $table->string('name');
            $table->integer('year')->default(1);
            $table->integer('status')->default(1);
            $table->integer('unit_id');
            $table->string('attachment')->nullable();
            $table->text('type_costs')->nullable()->comment('Tất cả loại chi phí');
            $table->integer('created_by')->default(1)->nullable();
            $table->integer('updated_by')->default(1)->nullable();
            $table->integer('unit_by')->default(0)->nullable();
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
        Schema::dropIfExists('el_training_plan');
    }
}

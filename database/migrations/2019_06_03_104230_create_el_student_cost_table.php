<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElStudentCostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('el_student_cost')) {
            Schema::create('el_student_cost', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->integer('status');
                $table->integer('created_by')->nullable()->default(2)->index();
                $table->integer('updated_by')->nullable()->default(2)->index();
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
        Schema::dropIfExists('el_student_cost');
    }
}

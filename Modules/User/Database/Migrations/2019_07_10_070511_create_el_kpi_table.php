<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElKpiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('el_kpi')) {
            Schema::create('el_kpi', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('user_code');
                $table->integer('year');
                $table->string('quarter_1', 50)->nullable();
                $table->string('quarter_2', 50)->nullable();
                $table->string('quarter_3', 50)->nullable();
                $table->string('quarter_4', 50)->nullable();
                $table->string('quarter_year', 50)->nullable();
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
        Schema::dropIfExists('el_kpi');
    }
}

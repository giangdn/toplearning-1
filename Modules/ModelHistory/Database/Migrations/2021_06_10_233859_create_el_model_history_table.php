<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElModelHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_model_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('model_id')->index();
            $table->string('model',100)->index();
            $table->string('code')->nullable();
            $table->string('action',500)->nullable();
            $table->longText('note')->nullable();
            $table->bigInteger('parent_id')->nullable()->index();
            $table->string('parent_model',100)->nullable()->index();
            $table->string('created_name')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('unit_by')->nullable();
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
        Schema::dropIfExists('el_model_history');
    }
}

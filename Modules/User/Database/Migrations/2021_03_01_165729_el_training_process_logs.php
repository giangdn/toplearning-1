<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ElTrainingProcessLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_training_process_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('process_id');
            $table->string('module')->nullable();
            $table->string('action')->nullable();
            $table->integer('type')->nullable()->comment('1: gộp, 2: tách, 3: hoàn thành quá trình đào tạo, 4: chuyển quá trình đào tạo');
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('el_training_process_logs');
    }
}

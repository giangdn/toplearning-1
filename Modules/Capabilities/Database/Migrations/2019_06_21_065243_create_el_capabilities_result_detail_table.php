<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElCapabilitiesResultDetailTable extends Migration
{
    public function up()
    {
        Schema::create('el_capabilities_result_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('result_id');
            $table->bigInteger('user_id');
            $table->bigInteger('subject_id');
            $table->string('subject_code');
            $table->string('subject_name');
            $table->bigInteger('capabilities_id');
            $table->string('capabilities_code');
            $table->string('capabilities_name');
            $table->integer('priority_level')->comment('Mức độ ưu tiên');
            $table->string('training_time');
            $table->string('training_form')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_capabilities_result_detail');
    }
}

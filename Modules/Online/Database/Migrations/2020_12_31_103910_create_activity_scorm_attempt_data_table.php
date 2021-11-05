<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityScormAttemptDataTable extends Migration
{
    public function up()
    {
        Schema::create('el_activity_scorm_attempt_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('attempt_id')->index();
            $table->string('var_name', 150)->index();
            $table->text('var_value');
            $table->unique(['attempt_id', 'var_name']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_activity_scorm_attempt_data');
    }
}

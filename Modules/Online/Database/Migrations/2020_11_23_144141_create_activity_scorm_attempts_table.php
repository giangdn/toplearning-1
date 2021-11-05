<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityScormAttemptsTable extends Migration
{
    public function up()
    {
        Schema::create('el_activity_scorm_attempts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('activity_id')->index();
            $table->bigInteger('user_id')->index();
            $table->bigInteger('user_type')->default(1);
            $table->integer('attempt')->index();
            $table->string('lesson_location', 100)->nullable();
            $table->timestamps();
            $table->unique(['activity_id', 'user_id', 'attempt'], 'activity_scorm_key_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_activity_scorm_attempts');
    }
}

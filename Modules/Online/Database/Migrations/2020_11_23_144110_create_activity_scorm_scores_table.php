<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityScormScoresTable extends Migration
{
    public function up()
    {
        Schema::create('el_activity_scorm_scores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->index();
            $table->bigInteger('user_type')->default(1);
            $table->bigInteger('activity_id')->index();
            $table->bigInteger('attempt_id')->index();
            $table->float('score_max')->nullable();
            $table->float('score_min')->nullable();
            $table->float('score_raw')->nullable();
            $table->float('score')->nullable();
            $table->string('status', 50)->nullable();
            $table->timestamps();
            $table->unique(['activity_id', 'attempt_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_activity_scorm_scores');
    }
}

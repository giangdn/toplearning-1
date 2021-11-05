<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElCapabilitiesReviewDetailTable extends Migration
{
    public function up()
    {
        Schema::create('el_capabilities_review_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('review_id');
            $table->bigInteger('group_id');
            $table->string('group_name');
            $table->integer('number');
            $table->bigInteger('captitle_id');
            $table->bigInteger('capabilities_id');
            $table->string('capabilities_code');
            $table->string('capabilities_name');
            $table->integer('standard_weight')
                ->comment('trọng số chuẩn');
            $table->integer('standard_critical_level')
                ->comment('mức độ quan trọng chuẩn');
            $table->integer('standard_level')
                ->comment('cấp độ chuẩn');
            $table->double('standard_goal')
                ->comment('điểm chuẩn');
            $table->integer('practical_level')
                ->comment('cấp độ thực tế');
            $table->double('practical_goal')
                ->comment('điểm thực tế');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_capabilities_review_detail');
    }
}

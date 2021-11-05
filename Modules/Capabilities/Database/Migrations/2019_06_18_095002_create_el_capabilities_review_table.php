<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElCapabilitiesReviewTable extends Migration
{
    public function up()
    {
        Schema::create('el_capabilities_review', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('name');
            $table->integer('status')->default(0);
            $table->integer('count_send')->default(0);
            $table->integer('count_save')->default(0);
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->float('sum_goal')->nullable();
            $table->float('sum_practical_goal')->nullable();
            $table->string('convent_id')->nullable()->comment('el_capabilities_convention_percent id');
            $table->text('comment')->nullable()->comment('Nhận xét khung năng lực');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_capabilities_review');
    }
}

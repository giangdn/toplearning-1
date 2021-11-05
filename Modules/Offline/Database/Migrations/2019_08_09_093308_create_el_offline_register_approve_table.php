<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOfflineRegisterApproveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_offline_register_approve', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('register_id');
            $table->bigInteger('course_id');
            $table->bigInteger('user_id');
            $table->bigInteger('approve_by')->nullable();
            $table->string('approved_step')->nullable();
            $table->tinyInteger('status')->nullable()->comment('2 chưa duyệt / 0 từ chối / 1 đã duyệt');
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
        Schema::dropIfExists('el_offline_register_approve');
    }
}

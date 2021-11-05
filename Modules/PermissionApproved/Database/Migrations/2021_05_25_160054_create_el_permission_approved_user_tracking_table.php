<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElPermissionApprovedUserTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_permission_approved_user_tracking', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('level');
            $table->integer('unit_id');
            $table->integer('unit_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->string('model_approved');
            $table->integer('user_id');
            $table->integer('permission_approved_id')->index('approved_id_index');
            $table->integer('permission_approved_hist_id')->index('hist_id_index');
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
        Schema::dropIfExists('el_permission_approved_user_tracking');
    }
}

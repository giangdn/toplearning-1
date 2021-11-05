<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElPermissionApprovedTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_permission_approved_tracking', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('level')->nullable();
            $table->integer('unit_id');
            $table->integer('unit_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->string('model_approved')->nullable();
            $table->integer('permission_approved_id');
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
        Schema::dropIfExists('el_permission_approved_tracking');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElApprovedModelTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_approved_model_tracking', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('model')->comment('ten table');
            $table->integer('model_id')->comment('id table');
            $table->integer('level')->comment('cấp độ');
            $table->integer('status')->nullable()->comment('trạng thái phê duyệt 1 đồng ý, 0 từ chối, null chưa duyệt');
            $table->string('note',255)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('unit_by')->nullable();
            $table->string('created_by_name')->nullable();
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
        Schema::dropIfExists('el_approved_model_tracking');
    }
}

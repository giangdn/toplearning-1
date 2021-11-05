<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElPermissionApprovedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_permission_approved', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('level')->nullable();
            $table->integer('unit_id');
            $table->integer('unit_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->string('model_approved')->nullable();
            $table->tinyInteger('has_change')->nullable()->default(0)->comment('1 có thay đổi');
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
        Schema::dropIfExists('el_permission_approved');
    }
}

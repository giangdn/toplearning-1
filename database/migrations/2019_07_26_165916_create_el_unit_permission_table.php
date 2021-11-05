<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElUnitPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_unit_permission', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('unit_id')->comment('Đơn vị');
            $table->bigInteger('user_id')->comment('Nhân viên quản lý đơn vị');
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
        Schema::dropIfExists('el_unit_permission');
    }
}

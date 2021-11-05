<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElPermissionTypeUnitTable extends Migration
{
    /**
     * Run the migrations.
     * Loại nhóm quyền custom thêm thủ công
     * @return void
     */
    public function up()
    {
        Schema::create('el_permission_type_unit', function (Blueprint $table) {
            $table->bigInteger('permission_type_id');
            $table->integer('unit_id');
            $table->string('type')->comment('owner,group-child');
            $table->primary(['permission_type_id','unit_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_permission_type_unit');
    }
}

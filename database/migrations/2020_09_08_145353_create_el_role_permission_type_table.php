<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElRolePermissionTypeTable extends Migration
{
    public function up()
    {
        Schema::create('el_role_permission_type', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('permission_id');
            $table->integer('permission_type_id');
            $table->primary(['role_id','permission_id','permission_type_id'], 'mdl_el_role_permission_type_role_primary');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_role_permission_type');
    }
}

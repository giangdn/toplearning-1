<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElPermissionGroupDetailTable extends Migration
{
    public function up()
    {
        Schema::create('el_permission_group_detail', function (Blueprint $table) {
            $table->bigInteger('permission_group_id');
            $table->bigInteger('permission_id');
            $table->primary(['permission_group_id', 'permission_id'], 'permission_group_detail_primary');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_permission_group_detail');
    }
}

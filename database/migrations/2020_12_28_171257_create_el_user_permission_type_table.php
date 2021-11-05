<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElUserPermissionTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_user_permission_type', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('permission_id');
            $table->integer('permission_type_id');
            $table->primary(['user_id','permission_id','permission_type_id'], 'mdl_el_user_permission_type_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_user_permission_type');
    }
}

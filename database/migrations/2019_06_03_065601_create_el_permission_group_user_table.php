<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElPermissionGroupUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_permission_group_user', function (Blueprint $table) {
            $table->bigInteger('permission_group_id')->unsigned();
            $table->bigInteger('user_id');
            $table->primary(['permission_group_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_permission_group_user');
    }
}

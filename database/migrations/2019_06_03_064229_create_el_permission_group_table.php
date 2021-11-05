<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElPermissionGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //if (!Schema::hasTable('el_permission_group')) {
            Schema::create('el_permission_group', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->bigInteger('created_by');
                $table->bigInteger('updated_by');
                $table->timestamps();
            });
        //}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_permission_group');
    }
}

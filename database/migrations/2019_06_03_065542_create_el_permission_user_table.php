<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElPermissionUserTable extends Migration
{
    
    public function up()
    {
        Schema::create('el_permission_user', function (Blueprint $table) {
            $table->string('permission_code', 150)->index();
            $table->bigInteger('user_id')->index();
            $table->bigInteger('unit_id')->index()->default(0);
            $table->primary(['permission_code', 'user_id', 'unit_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_permission_user');
    }
}

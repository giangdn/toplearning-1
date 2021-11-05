<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonChargeRolesTable extends Migration
{
    public function up()
    {
        Schema::create('el_person_charge_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('person_charge_id');
            $table->bigInteger('role_id');
            $table->unique(['person_charge_id', 'role_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_person_charge_roles');
    }
}

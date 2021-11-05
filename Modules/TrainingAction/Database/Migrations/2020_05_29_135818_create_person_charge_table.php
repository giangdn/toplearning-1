<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonChargeTable extends Migration
{
    public function up()
    {
        Schema::create('el_person_charge', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->bigInteger('field_id');
            $table->integer('max_support')->default(0);
            $table->tinyInteger('type')->default(1)->comment('1: Chính, 2: phụ');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('el_person_charge');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('auth', 50)->default('manual');
            $table->string('code',30)->nullable();
            $table->string('username', 150)->unique();
            $table->string('password');
            $table->string('firstname', 150);
            $table->string('lastname', 150);
            $table->string('email', 200)->nullable();
            $table->dateTime('last_online')->nullable();
            $table->string('remember_token')->nullable();
            $table->tinyInteger('api')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user');
    }
}

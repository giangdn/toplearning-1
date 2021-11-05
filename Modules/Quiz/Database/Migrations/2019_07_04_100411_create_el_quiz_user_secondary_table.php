<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuizUserSecondaryTable extends Migration
{
    public function up()
    {
        Schema::create('el_quiz_user_secondary', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 150)->unique();
            $table->string('name');
            $table->string('username');
            $table->string('password');
            $table->dateTime('dob')->nullable();
            $table->string('email')->nullable();
            $table->string('identity_card', 15)->nullable()->comment('CMND');
            $table->integer('created_by')->default(2)->nullable()->index();
            $table->integer('updated_by')->default(2)->nullable()->index();
            $table->integer('unit_by')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_quiz_user_secondary');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElQuizUserReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_quiz_user_review', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('user_type')->default(1);
            $table->string('user_code');
            $table->string('full_name');
            $table->string('username')->nullable();
            $table->string('email')->nullable();
            $table->integer('unit_id')->nullable();
            $table->string('unit_name')->nullable();
            $table->integer('parent_unit_id')->nullable();
            $table->string('parent_unit_name')->nullable();
            $table->integer('title_id')->nullable();
            $table->string('title_name')->nullable();
            $table->integer('quiz_id');
            $table->integer('part_id');
            $table->longText('content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_quiz_user_review');
    }
}

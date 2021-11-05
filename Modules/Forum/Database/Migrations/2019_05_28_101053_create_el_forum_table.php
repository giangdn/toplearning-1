<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElForumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_forum', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('icon')->nullable();
            $table->string('name')->nullable();
            $table->integer('status')->default(0);
            $table->bigInteger('category_id')->nullable();
            $table->integer('num_topic')->default(0);
            $table->integer('num_comment')->default(0);
            $table->integer('created_by');
            $table->integer('updated_by');
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
        Schema::dropIfExists('el_forum');
    }
}

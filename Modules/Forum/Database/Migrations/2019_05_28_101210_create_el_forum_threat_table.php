<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElForumThreatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_forum_thread', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->longText('content');
            $table->integer('forum_id');
            $table->integer('main_article')->nullable();
            $table->integer('status')->default(0);
            $table->integer('views')->default(0);
            $table->integer('total_comment')->default(0);
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->string('hashtag')->nullable();
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
        Schema::dropIfExists('el_forum_thread');
    }
}

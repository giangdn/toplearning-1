<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElForumUserLikeCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_forum_user_like_comment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('thread_id');
            $table->bigInteger('user_id');
            $table->bigInteger('comment_id');
            $table->integer('like')->nullable();
            $table->integer('dislike')->nullable();
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
        Schema::dropIfExists('el_forum_user_like_comment');
    }
}

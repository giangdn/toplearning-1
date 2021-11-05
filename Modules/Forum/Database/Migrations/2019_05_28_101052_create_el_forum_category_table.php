<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElForumCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_forum_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('icon');
            $table->integer('status')->default(0);
            $table->integer('created_by')->nullable()->default(2)->index();
            $table->integer('updated_by')->nullable()->default(2)->index();
            $table->integer('unit_by')->nullable()->index();
            $table->string('unit_id')->nullable();
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
        Schema::dropIfExists('el_forum_category');
    }
}

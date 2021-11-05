<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElNewsOutsideCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_news_outside_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('icon')->nullable();
            $table->string('name');
            $table->bigInteger('parent_id')->nullable();
            $table->integer('status')->default(0);
            $table->integer('sort')->nullable()->default(0);
            $table->integer('stt_sort')->nullable();
            $table->integer('stt_sort_parent')->nullable()->default(0);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('unit_by')->nullable();
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
        Schema::dropIfExists('el_news_outside_category');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElNewsLinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_news_link', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('news_id');
            $table->string('title')->nullable();
            $table->string('link');
            $table->string('type')->default('link');
            $table->integer('created_by')->default(2);
            $table->integer('updated_by')->default(2);
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
        Schema::dropIfExists('el_news_link');
    }
}

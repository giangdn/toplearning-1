<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElSliderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_slider', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image')->comment('hình ảnh');
            $table->string('description')->nullable();
            $table->string('location')->nullable()->comment('vị trí');
            $table->string('object')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('display_order')->default(1)->comment('thứ tự hiển thị');
            $table->integer('type')->default(1)->comment('1: hiện trên web; 2: hiện trên mobile');
            $table->string('url')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('el_slider');
    }
}

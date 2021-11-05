<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElLogoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_logo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image')->comment('hình ảnh');
            $table->string('object')->nullable();
            $table->integer('status')->default(1);
            $table->integer('type')->default(1)->comment('1: hiện trên web; 2: hiện trên mobile');
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
        Schema::dropIfExists('el_logo');
    }
}

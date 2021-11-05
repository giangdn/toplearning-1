<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElAdvertisingPhotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_advertising_photo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image')->comment('hình ảnh');
            $table->integer('type')->comment('0:Tin tức chung, 1:tin tức');
            $table->string('url')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->bigInteger('unit_by')->nullable();
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
        Schema::dropIfExists('el_advertising_photo');
    }
}

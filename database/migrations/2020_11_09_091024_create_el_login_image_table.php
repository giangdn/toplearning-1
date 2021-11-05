<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElLoginImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_login_image', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image')->comment('hình ảnh');
            $table->integer('type')->default(1)->comment('1:web; 2:mobile');
            $table->tinyInteger('status')->default(0);
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');
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
        Schema::dropIfExists('el_login_image');
    }
}

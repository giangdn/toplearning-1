<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElLibrariesObjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_libraries_object', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('libraries_id');
            $table->integer('type')->comment('loại thư viện, 2: ebook, 3:tài liệu');
            $table->integer('status')->nullable()->comment('1: xem, 2: tải, 3: cả 2');
            $table->bigInteger('title_id')->nullable();
            $table->bigInteger('unit_id')->nullable();
            $table->bigInteger('user_id')->nullable();
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
        Schema::dropIfExists('el_libraries_object');
    }
}

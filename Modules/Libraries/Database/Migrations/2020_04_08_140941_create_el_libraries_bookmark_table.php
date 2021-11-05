<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElLibrariesBookmarkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_libraries_bookmark', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('libraries_id');
            $table->integer('type')->comment('loại thư viện. 1:sách, 2: ebook, 3:tài liệu');
            $table->bigInteger('user_id');
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
        Schema::dropIfExists('el_libraries_bookmark');
    }
}

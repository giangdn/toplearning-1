<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElLibrariesCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_libraries_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->bigInteger('parent_id')->nullable();
            $table->integer('type')->comment('1: Danh mục sạch, 2: Danh mục ebook, 3:Danh mục tài liệu,4:Danh mục video, 5:Danh mục sách nói');
            $table->integer('created_by')->nullable()->default(2);
            $table->integer('updated_by')->nullable()->default(2);
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
        Schema::dropIfExists('el_libraries_category');
    }
}

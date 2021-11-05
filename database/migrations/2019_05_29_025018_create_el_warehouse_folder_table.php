<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElWarehouseFolderTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('el_warehouse_folder')) {
            return;
        }

        Schema::create('el_warehouse_folder', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('type', 100)->default('image');
//            $table->bigInteger('user_id')->index();
            $table->bigInteger('parent_id')->index()->nullable();
            $table->integer('unit_by')->index()->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_warehouse_folder');
    }
}

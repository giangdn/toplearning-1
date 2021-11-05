<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElWarehouseTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('el_warehouse')) {
            return;
        }

        Schema::create('el_warehouse', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('file_name');
            $table->string('file_type', 150);
            $table->string('file_path', 150)->index();
            $table->bigInteger('file_size');
            $table->string('extension', 50);
            $table->string('source', 50);
            $table->string('type', 50)->default('image')->index();
            $table->bigInteger('folder_id')->index()->nullable();
//            $table->bigInteger('user_id')->index();
            $table->integer('check_role')->nullable();
            $table->integer('unit_by')->index()->nullable();
            $table->integer('user_id')->default(2)->index()->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_warehouse');
    }
}

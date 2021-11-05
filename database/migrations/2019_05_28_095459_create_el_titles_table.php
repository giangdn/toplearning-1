<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElTitlesTable extends Migration
{
    public function up()
    {
        Schema::create('el_titles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 150)->unique();
            $table->string('name');
            $table->string('group')->nullable()->comment('KD, HT');
            $table->bigInteger('position_id')->nullable()->index();
            $table->bigInteger('unit_id')->nullable()->index();
            $table->integer('unit_level')->nullable()->index();
            $table->integer('unit_type')->nullable()->index();
            $table->integer('status')->default(0);
            $table->integer('created_by')->nullable()->default(2)->index();
            $table->integer('updated_by')->nullable()->default(2)->index();
            $table->integer('unit_by')->nullable()->index();
            $table->integer('employees')->nullable()->comment('số nhân viên theo chức danh');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_titles');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitManagerSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_unit_manager_setting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('unit_id')->index();
            $table->string('priority1',500)->nullable()->comment('Ưu tiên 1');
            $table->string('priority2',500)->nullable()->comment('Ưu tiên 2');
            $table->string('priority3',500)->nullable()->comment('Ưu tiên 3');
            $table->string('priority4',500)->nullable()->comment('Ưu tiên 4');
            $table->integer('unit_by')->index()->nullable();
            $table->bigInteger('created_by')->index()->default(2);
            $table->bigInteger('updated_by')->index()->default(2);
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
        Schema::dropIfExists('el_unit_manager_setting');
    }
}

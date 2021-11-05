<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElCapabilitiesTitleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_capabilities_title', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('number_title')->comment('Số thứ tự trong 1 chức danh');
            $table->bigInteger('capabilities_id')->unsigned();
            $table->bigInteger('title_id')->unsigned();
            $table->integer('weight')->default(0)->comment('trọng số');
            $table->integer('critical_level')->default(0)->comment('mức độ quan trọng');
            $table->integer('level')->default(0)->comment('cấp độ');
            $table->double('goal')->default(0)->comment('điểm chuẩn');
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
        Schema::dropIfExists('el_capabilities_title');
    }
}

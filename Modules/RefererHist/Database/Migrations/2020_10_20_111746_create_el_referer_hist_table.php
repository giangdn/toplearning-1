<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElRefererHistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_referer_hist', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('referer')->comment('Mã người giới thiệu');
            $table->bigInteger('user_id');
            $table->integer('point')->comment('điểm tích lũy');
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
        Schema::dropIfExists('el_referer_hist');
    }
}

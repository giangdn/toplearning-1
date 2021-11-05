<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElPotentialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_potential', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->float('ratio')->nullable()->comment('Tỷ lệ đánh giá');
            $table->string('group_percent')->nullable()->comment('Nhóm');
            $table->string('d1')->nullable()->comment('Quý 1');
            $table->string('d2')->nullable()->comment('Quý 2');
            $table->string('d3')->nullable()->comment('Quý 3');
            $table->dateTime('start_date')->comment('Ngày bắt đầu');
            $table->dateTime('end_date')->comment('Ngày kết thúc');
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
        Schema::dropIfExists('el_potential');
    }
}

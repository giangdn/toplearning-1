<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOfflineResultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_offline_result', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('register_id');
            $table->bigInteger('user_id');
            $table->bigInteger('course_id');
            $table->double('percent')->nullable()->comment('Phần trăm');
            $table->double('pass_score')->nullable()->comment('Điểm để đạt');
            $table->double('score_1')->nullable()->comment('Điểm lần 1');
            $table->double('score_2')->nullable()->comment('Điểm lần 2');
            $table->string('score')->nullable()->comment('Điểm cuối cùng');
            $table->integer('result')->default(-1)->comment('Kết quả');
            $table->string('note')->nullable();
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
        Schema::dropIfExists('el_offline_result');
    }
}

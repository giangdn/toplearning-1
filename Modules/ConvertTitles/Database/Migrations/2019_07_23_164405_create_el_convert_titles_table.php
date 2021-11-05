<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElConvertTitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_convert_titles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->integer('title_old')->comment('Chức danh ban đầu');
            $table->bigInteger('title_id')->comment('Chức danh chuyển đổi');
            $table->bigInteger('unit_id')->comment('Đơn vị tập huấn');
            $table->bigInteger('unit_receive_id')->comment('Đơn vị nhận');
            $table->dateTime('start_date')->comment('Ngày bắt đầu');
            $table->dateTime('end_date')->comment('Ngày kết thúc');
            $table->dateTime('send_date')->nullable()->comment('Ngày gửi đánh giá');
            $table->text('note')->nullable();
            $table->string('file_reviews_unit')->nullable()->comment('file đánh giá của trưởng đơn vị');
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
        Schema::dropIfExists('el_convert_titles');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElNotifySendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_notify_send', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('subject')->comment('Tiêu đề thông báo');
            $table->text('content')->nullable()->comment('Nội dung thông báo');
            $table->text('url')->nullable()->comment('Liên kết đích của thông báo');
            $table->tinyInteger('user_type')->nullable()->comment('Loại đối tượng');
            $table->tinyInteger('popup')->default(0)->comment('Hiển thị popup');
            $table->tinyInteger('popup_type')->default(0)->comment('Loại popup');
            $table->string('popup_image')->nullable();
            $table->bigInteger('created_by')->nullable()->comment('Người gửi');
            $table->bigInteger('updated_by')->nullable();
            $table->integer('unit_by')->nullable();
            $table->tinyInteger('status')->default(0)->comment('Bật / tắt');
            $table->tinyInteger('viewed')->default(0)->comment('Đã xem');
            $table->tinyInteger('important')->default(0)->comment('tin quan trọng');
            $table->dateTime('time_send')->nullable()->comment('thời gian gửi');
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
        Schema::dropIfExists('el_notify_send');
    }
}

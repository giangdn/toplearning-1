<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElNotifyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_notify', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('subject')->comment('Tiêu đề thông báo');
            $table->text('content')->nullable()->comment('Nội dung thông báo');
            $table->text('url')->nullable()->comment('Liên kết đến của thông báo');
            $table->bigInteger('created_by')->comment('Người gửi / 0: hệ thống');
            $table->tinyInteger('viewed')->default(0)->comment('Đã xem');
            $table->tinyInteger('important')->default(0)->comment('tin quan trọng');
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
        Schema::dropIfExists('el_notify');
    }
}

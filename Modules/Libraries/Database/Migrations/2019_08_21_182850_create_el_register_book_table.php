<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElRegisterBookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_register_book', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->bigInteger('book_id');
            $table->integer('quantity')->comment('Số lượng');
            $table->dateTime('borrow_date')->comment('Ngày mượn')->nullable();
            $table->dateTime('pay_date')->comment('Ngày trả')->nullable();
            $table->dateTime('user_return_book')->comment('Người dùng trả sách')->nullable();
            $table->dateTime('register_date')->comment('Ngày đăng ký');
            $table->integer('approved')->default(2)->comment('1: Duyệt, 0:Từ chối');
            $table->integer('status')->default(1)->comment('1:Chưa lấy sách, 2: Đang mượn sách, 3:Đã trả');
            $table->integer('created_by')->nullable()->default(2);
            $table->integer('updated_by')->nullable()->default(2);
            $table->integer('unit_by')->nullable();
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
        Schema::dropIfExists('el_register_book');
    }
}

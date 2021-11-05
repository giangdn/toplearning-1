<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElProfileTable extends Migration
{
    public function up()
    {
        Schema::create('el_profile', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('code', 150)->unique();
            $table->bigInteger('user_id')->unique()->index();
            $table->string('firstname')->comment('Tên nhân viên');
            $table->string('lastname')->comment('Họ nhân viên');
            $table->dateTime('dob')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('identity_card')->nullable()->comment('Số CMND');
            $table->dateTime('date_range')->nullable()->comment('Ngày cấp');
            $table->string('issued_by')->nullable()->comment('Nơi cấp');
            $table->integer('gender')->default(1)->comment('1:Nam, 0:Nữ');
            $table->string('phone', 50)->nullable();
            $table->dateTime('contract_signing_date')->nullable()->comment('Ngày kí hợp đồng lao động');
            $table->dateTime('effective_date')->nullable()->comment('Ngày hiệu lực');
            $table->dateTime('expiration_date')->nullable()->comment('Ngày kết thúc');
            $table->dateTime('date_off')->nullable()->comment('Ngày nghỉ việc');
            $table->dateTime('join_company')->nullable()->comment('Ngày vào ngân hàng');
            $table->string('expbank')->nullable()->comment('Thâm niên trong lĩnh vực ngân hàng');
            $table->integer('position_id')->nullable()->index();
            $table->string('title_code', 150)->nullable()->index();
            $table->integer('title_id')->nullable()->index();
            $table->string('unit_code', 150)->nullable()->index();
            $table->integer('unit_id')->nullable()->index();
            $table->string('area_code', 150)->nullable()->index();
            $table->string('level')->nullable();
            $table->string('certificate_code', 150)->index()->nullable()->comment('Mã trình độ');
            $table->integer('status')->default(1)->comment('0: Nghỉ việc, 1: Đang làm, 2: Thử việc, 3: Tạm hoãn');
            $table->string('avatar')->nullable();
            $table->string('id_code', 150)->index()->nullable()->comment('Mã định danh');
            $table->string('referer', 150)->index()->nullable()->comment('Mã người giới thiệu');
            $table->string('like_new')->nullable()->comment('bài viết đã thích');
            $table->integer('role')->nullable();
            $table->integer('type_user')->default(1);
            $table->dateTime('date_title_appointment')->nullable();
            $table->dateTime('end_date_title_appointment')->nullable();
            $table->tinyInteger('marriage')->nullable()->comment('tình trạng hôn nhân');
            $table->tinyInteger('leave_type_id')->nullable()->comment('Loại nghỉ');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('el_profile');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElProfileViewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_profile_view', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('code', 150)->unique();
            $table->bigInteger('user_id')->index()->unique();
            $table->string('firstname')->nullable()->comment('Họ');
            $table->string('lastname')->nullable()->comment('Tên');
            $table->string('full_name')->comment('Họ Tên nhân viên');
            $table->dateTime('dob')->nullable()->comment('ngày sinh');
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
            $table->integer('position_id')->nullable()->index()->comment('id chức vụ');
            $table->string('position_code')->nullable()->comment('Mã chức vụ');
            $table->string('position_name')->nullable()->comment('tên chức vụ');
            $table->integer('title_id')->nullable()->index()->comment('id chức danh');
            $table->string('title_code')->nullable()->comment('mã chức danh');
            $table->string('title_name')->nullable()->comment('chức danh');
            $table->integer('unit_id')->nullable()->index()->comment('id đơn vị');
            $table->string('unit_code')->nullable()->comment('mã đơn vị');
            $table->string('unit_name')->nullable()->comment('tên đơn vị');
            $table->integer('parent_unit_id')->nullable()->index()->comment('id đơn vị cha');
            $table->string('parent_unit_code')->nullable()->comment('mã đơn vị cha');
            $table->string('parent_unit_name')->nullable()->comment('tên đơn vị cha');
            $table->integer('area_id')->nullable()->index()->comment('id khu vực');
            $table->string('area_code')->nullable()->comment('mã khu vực');
            $table->string('area_name')->nullable()->comment('Tên khu vực');
            $table->string('level')->nullable();
            $table->integer('certificate_id')->nullable()->comment('Mã trình độ');
            $table->string('certificate_name')->nullable()->comment('trình độ');
            $table->integer('status_id')->default(1)->nullable()->comment('0: Nghỉ việc, 1: Đang làm, 2: Thử việc, 3: Tạm hoãn');
            $table->string('status_name')->nullable()->comment('Tên trạng thái');
            $table->string('avatar')->nullable();
            $table->string('id_code', 150)->nullable()->comment('Mã định danh');
            $table->string('referer', 150)->nullable()->comment('Mã người giới thiệu');
            $table->integer('type_user')->default(1);
            $table->dateTime('date_title_appointment')->nullable();
            $table->dateTime('end_date_title_appointment')->nullable();
            $table->tinyInteger('marriage')->nullable()->comment('tình trạng hôn nhân');
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
        Schema::dropIfExists('el_profile_view');
    }
}

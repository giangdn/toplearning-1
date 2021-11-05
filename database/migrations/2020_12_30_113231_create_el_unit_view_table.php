<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElUnitViewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_unit_view', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('unit0_id')->nullable();
            $table->string('unit0_code')->nullable();
            $table->string('unit0')->nullable();
            $table->integer('unit1_id')->nullable();
            $table->string('unit1_code')->nullable();
            $table->string('unit1')->nullable()->comment('Tập đoàn');
            $table->integer('unit2_id')->nullable();
            $table->string('unit2_code')->nullable();
            $table->string('unit2')->nullable()->comment('Công ty');
            $table->integer('unit3_id')->nullable();
            $table->string('unit3_code')->nullable();
            $table->string('unit3')->nullable()->comment('Phòng ban level 3');
            $table->integer('unit4_id')->nullable();
            $table->string('unit4_code')->nullable();
            $table->string('unit4')->nullable()->comment('Kênh quản lý level 4');
            $table->integer('unit5_id')->nullable();
            $table->string('unit5_code')->nullable();
            $table->string('unit5')->nullable()->comment('Bộ phận gián tiếp level 5');
            $table->integer('unit6_id')->nullable();
            $table->string('unit6_code')->nullable();
            $table->string('unit6')->nullable()->comment('Bộ phận trực tiếp level 6');
            $table->integer('unit7_id')->nullable();
            $table->string('unit7_code')->nullable();
            $table->string('unit7')->nullable()->comment('Vùng level 7');
            $table->integer('unit8_id')->nullable();
            $table->string('unit8_code')->nullable();
            $table->string('unit8')->nullable()->comment('Khu vực level 8');
            $table->integer('unit9_id')->nullable();
            $table->string('unit9_code')->nullable();
            $table->string('unit9')->nullable()->comment('Chi nhánh tỉnh level 9');
            $table->integer('unit10_id')->nullable();
            $table->string('unit10_code')->nullable();
            $table->string('unit10')->nullable()->comment('Cửa hàng level 10');
            $table->integer('object_id');
            $table->integer('status');
            $table->string('unit_code')->comment('Mã đơn vị');
            $table->integer('area_id')->index()->nullable()->comment('id khu vực');
            $table->string('area_code')->nullable()->comment('Mã khu vực');
            $table->string('area_name')->nullable()->comment('Tên khu vực');
            $table->integer('area_level')->nullable()->comment('cấp độ khu vực');
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
        Schema::dropIfExists('el_unit_view');
    }
}

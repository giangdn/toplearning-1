<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElPermissionTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('el_permission_type')) {
            Schema::create('el_permission_type', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->integer('type')->comment('1: mặc định, 2:custom');
                $table->string('description')->nullable()->comment('Miêu tả');
                $table->integer('sort')->nullable()->comment('sắp xếp');
                $table->bigInteger('created_by')->nullable()->index();
                $table->bigInteger('updated_by')->nullable()->index();
                $table->integer('unit_by')->nullable()->index();
                $table->timestamps();
            });
            DB::table('el_permission_type')->insert([
                [
                    'name'=>'All',
                    'type'=>1,
                    'description'=>'Thấy tất cả',
                    'sort'=>1,
                    'created_by'=>1,
                    'updated_by'=>1,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s')
                ],
                [
                    'name'=>'Global',
                    'type'=>1,
                    'description'=>'Thấy trong công ty',
                    'sort'=>2,
                    'created_by'=>1,
                    'updated_by'=>1,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s')
                ],
                [
                    'name'=>'Group',
                    'type'=>1,
                    'description'=>'Thấy trong đơn vị',
                    'sort'=>3,
                    'created_by'=>1,
                    'updated_by'=>1,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s')
                ],
                [
                    'name'=>'Group-Child',
                    'type'=>1,
                    'description'=>'Thấy trong đơn vị và đơn vị con',
                    'sort'=>4,
                    'created_by'=>1,
                    'updated_by'=>1,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s')
                ],
                [
                    'name'=>'Owner',
                    'type'=>1,
                    'description'=>'Chỉ thấy được những gì mình tạo',
                    'sort'=>5,
                    'created_by'=>1,
                    'updated_by'=>1,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s')
                ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_permission_type');
    }
}

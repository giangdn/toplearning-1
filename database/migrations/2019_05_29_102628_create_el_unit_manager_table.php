<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElUnitManagerTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('el_unit_manager')) {
            Schema::create('el_unit_manager', function (Blueprint $table) {
                $table->string('unit_code', 50)->index();
                $table->string('user_code', 50)->index();
                $table->integer('type')->default(2)->comment('1: đổ từ nhân sự qua');
                $table->integer('manager_type')->default(1)->comment('1: TĐV chính thức, 2: TĐV được ủy quyền');
                $table->primary(['unit_code', 'user_code'], 'unit_manager_primary');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('el_unit_manager');
    }
}

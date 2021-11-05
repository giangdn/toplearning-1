<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElBackendMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_backend_menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 150)->unique();
            $table->string('name');
            $table->string('route')->nullable();
            $table->string('permission', 255)->nullable();
            $table->string('parent_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_backend_menus');
    }

    public function insertData() {
        DB::table('el_backend_menus')->insert([
            [
                'code' => 'managerment',
                'name' => 'Quản lý',
                'route' => null,
                'permission' => null,
            ],
            [
                'code' => 'training',
                'name' => 'Đào tạo',
                'route' => null,
                'permission' => null,
            ],
            [
                'code' => 'news',
                'name' => 'Tin tức',
                'route' => null,
                'permission' => null,
            ],
            [
                'code' => 'library',
                'name' => 'Thư viên',
                'route' => null,
                'permission' => null,
            ],
            [
                'code' => 'quiz',
                'name' => 'Thi',
                'route' => null,
                'permission' => null,
            ],
            [
                'code' => 'setting',
                'name' => 'Cài đặt',
                'route' => null,
                'permission' => null,
            ]
        ]);
    }
}

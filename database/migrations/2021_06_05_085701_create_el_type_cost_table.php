<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElTypeCostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_type_cost', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('name');
            $table->timestamps();
        });

        DB::table('el_type_cost')->insert([
            [
                'code' => 'CPTC',
                'name' => 'Chi Phí tổ chức',
            ],
            [
                'code' => 'CPPĐT',
                'name' => 'Chi phí phòng đào tạo',
            ],
            [
                'code' => 'CPĐTBN',
                'name' => 'Chi phí đào tạo bên ngoài',
            ],
            [
                'code' => 'CPGV',
                'name' => 'Chi phí giảng viên',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_type_cost');
    }
}

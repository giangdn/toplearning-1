<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElUnitNameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //if (!Schema::hasTable('el_unit_name')) {
            Schema::create('el_unit_name', function (Blueprint $table) {
                $table->integer('level')->primary();
                $table->string('name');
                $table->string('name_en');
            });

            DB::table('el_unit_name')->insert(
                [
                    [
                        'level' => 1,
                        'name' => 'Đơn vị 1',
                        'name_en' => 'Unit 1'
                    ],
                    [
                        'level' => 2,
                        'name' => 'Đơn vị 2',
                        'name_en' => 'Unit 2'
                    ],
                    [
                        'level' => 3,
                        'name' => 'Đơn vị 3',
                        'name_en' => 'Unit 3'
                    ],
                    [
                        'level' => 4,
                        'name' => 'Đơn vị 4',
                        'name_en' => 'Unit 4'
                    ],
                    [
                        'level' => 5,
                        'name' => 'Đơn vị 5',
                        'name_en' => 'Unit 5'
                    ],
                    [
                        'level' => 6,
                        'name' => 'Đơn vị 6',
                        'name_en' => 'Unit 6'
                    ],
                    [
                        'level' => 7,
                        'name' => 'Đơn vị 7',
                        'name_en' => 'Unit 7'
                    ],
                    [
                        'level' => 8,
                        'name' => 'Đơn vị 8',
                        'name_en' => 'Unit 8'
                    ],
                    [
                        'level' => 9,
                        'name' => 'Đơn vị 9',
                        'name_en' => 'Unit 9'
                    ],
                    [
                        'level' => 10,
                        'name' => 'Đơn vị 10',
                        'name_en' => 'Unit 10'
                    ],
                    [
                        'level' => 11,
                        'name' => 'Đơn vị 11',
                        'name_en' => 'Unit 11'
                    ],
                    [
                        'level' => 12,
                        'name' => 'Đơn vị 12',
                        'name_en' => 'Unit 12'
                    ],
                    [
                        'level' => 13,
                        'name' => 'Đơn vị 13',
                        'name_en' => 'Unit 13'
                    ],
                    [
                        'level' => 14,
                        'name' => 'Đơn vị 14',
                        'name_en' => 'Unit 14'
                    ],
                    [
                        'level' => 15,
                        'name' => 'Đơn vị 15',
                        'name_en' => 'Unit 15'
                    ],
                ]
            );
        //}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_unit_name');
    }
}

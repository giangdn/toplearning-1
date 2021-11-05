<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElAreaNameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_area_name', function (Blueprint $table) {
            $table->integer('level')->primary();
            $table->string('name');
            $table->string('name_en');
        });

        DB::table('el_area_name')->insert(
            [
                [
                    'level' => 1,
                    'name' => 'Quốc gia',
                    'name_en' => 'Country'
                ],
                [
                    'level' => 2,
                    'name' => 'Miền',
                    'name_en' => 'Domain'
                ],
                [
                    'level' => 3,
                    'name' => 'Khu vực',
                    'name_en' => 'Area'
                ],
                [
                    'level' => 4,
                    'name' => 'Vùng',
                    'name_en' => 'Region'
                ],
                [
                    'level' => 5,
                    'name' => 'Văn phòng',
                    'name_en' => 'Office'
                ],
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_area_name');
    }
}

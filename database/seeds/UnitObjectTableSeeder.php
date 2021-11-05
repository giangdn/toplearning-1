<?php

use Illuminate\Database\Seeder;

class UnitObjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $unitObjects = DB::table('el_unit_object')->get();
         $data = [
             [
                 'id' => 1,
                 'code' => 'corp',
                 'name' => 'Tập đoàn',
                 'parent_id' => null,

             ],
             [
                 'id' => 2,
                 'code' => 'unit2',
                 'name' => 'Công ty',
                 'parent_id' => 1,

             ],
             [
                 'id' => 3,
                 'code' => 'unit3',
                 'name' => 'Phòng ban',
                 'parent_id' => 2,

             ],
             [
                 'id' => 4,
                 'code' => 'unit4',
                 'name' => 'Kênh quản lý',
                 'parent_id' => 3,

             ],
             [
                 'id' => 5,
                 'code' => 'unit5',
                 'name' => 'Bộ phận gián tiếp',
                 'parent_id' => 4,

             ],
             [
                 'id' => 6,
                 'code' => 'unit6',
                 'name' => 'Bộ phận trực tiếp',
                 'parent_id' => 5,

             ],
             [
                 'id' => 7,
                 'code' => 'unit7',
                 'name' => 'Vùng',
                 'parent_id' => 6,

             ],
             [
                 'id' => 8,
                 'code' => 'unit8',
                 'name' => 'Khu',
                 'parent_id' => 7,

             ],
             [
                 'id' => 9,
                 'code' => 'unit9',
                 'name' => 'Chi nhánh',
                 'parent_id' => 8,

             ],
             [
                 'id' => 10,
                 'code' => 'unit10',
                 'name' => 'Cửa hàng',
                 'parent_id' => 9,

             ],
         ];
        DB::table('el_unit_object')->insert($data);

    }
}

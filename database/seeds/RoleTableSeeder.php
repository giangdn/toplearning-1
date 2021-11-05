<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['Admin','Admin','Vai trò có quyền cao nhất'],
            ['Manager','Quản lý','Vai trò có tất cả quyền trừ quyền quản lý phần quyền'],
            ['Teacher','Giảng viên','Vai trò giảng viên'],
            ['Editor','Biên soạn tài liệu', 'Vai trờ người biên tập'],
            ['User','Người dùng', 'Vai trờ người dùng'],
        ];
        foreach ($roles as $key => $value) {
            Role::updateOrCreate(
                [
                'name' => $value[1]
                ],
                [
                    'code' => $value[0],
                    'type' => 1,
                    'guard_name' => 'web',
                    'description'=>$value[2],
                    'created_by'=>1,
                    'updated_by'=>1,
                ]
                );
        }
        User::find(1)->assignRole('Admin');
        User::find(2)->assignRole('Admin');
    }
}

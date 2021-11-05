<?php

namespace Modules\PermissionApproved\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\PermissionApproved\Entities\ModelApproved;

class ModelApprovedTableSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['model'=>'el_online_course','name'=>'Phê duyệt khóa học trực tuyến','status'=>1],
            ['model'=>'el_offline_course','name'=>'Phê duyệt khóa học tập trung','status'=>1],
            ['model'=>'el_online_register','name'=>'Phê duyệt ghi danh khóa học trực tuyến','status'=>1],
            ['model'=>'el_offline_register','name'=>'Phê duyệt ghi danh khóa học tập trung','status'=>1],
            ['model'=>'el_course_plan','name'=>'Phê duyệt kế hoạch đào tạo','status'=>1],
            ['model'=>'el_quiz','name'=>'Phê duyệt thi','status'=>1],
            ['model'=>'el_quiz_templates','name'=>'Phê duyệt cơ cấu đề thi','status'=>1],
        ];
        foreach ($data as $index => $item) {
            ModelApproved::updateOrCreate(['model'=>$item['model']],
                [
                    'name'=>$item['name'],
                    'status'=>$item['status']
                ]
            );
        }
//        ModelApproved::updateOrCreate(['model'=>'el_offline_course'],
//            [
//                'name'=>'Phê duyệt khóa học tập trung',
//                'status'=>1
//            ]
//            );
    }
}

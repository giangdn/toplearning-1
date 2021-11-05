<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionCapabilites extends Migration
{
    public function up()
    {
        /*DB::table('el_permission')->insert(
            [
                [
                    'code' => 'module_capabilities',
                    'name' => 'Khung năng lực',
                ]
            ]
        );

        DB::table('el_permission')->insert([
            [
                'code' => 'module.capabilities',
                'name' => 'Xem khung năng lực',
                'parent_code' => 'module_capabilities',
                'extend' => null
            ],
            [
                'code' => 'module.capabilities.create',
                'name' => 'Thêm khung năng lực',
                'parent_code' => 'module_capabilities',
                'extend' => null
            ],
            [
                'code' => 'module.capabilities.edit',
                'name' => 'Chỉnh sửa khung năng lực',
                'parent_code' => 'module_capabilities',
                'extend' => 'module.capabilities, module.capabilities.create'
            ],
            [
                'code' => 'module.capabilities.remove',
                'name' => 'Xóa khung năng lực',
                'parent_code' => 'module_capabilities',
                'extend' => null
            ]
        ]);

        DB::table('el_permission')->insert(
            [
                [
                    'code' => 'module.capabilities.review',
                    'name' => 'Xem đánh giá',
                    'parent_code' => 'module_capabilities',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.capabilities.review.user.create',
                    'name' => 'Tạo đánh giá',
                    'parent_code' => 'module_capabilities',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.capabilities.review.user.edit',
                    'name' => 'Sửa đánh giá',
                    'parent_code' => 'module_capabilities',
                    'unit_permission' => 1,
                    'extend' => 'module.capabilities.review, module.capabilities.review.user.create',
                ],
                [
                    'code' => 'module.capabilities.review.user.remove',
                    'name' => 'Xóa đánh giá',
                    'parent_code' => 'module_capabilities',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.capabilities.review.user.send',
                    'name' => 'Gửi đánh giá',
                    'parent_code' => 'module_capabilities',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.capabilities.review.result.index',
                    'name' => 'Xem xây dựng kế hoạch đào tạo',
                    'parent_code' => 'module_capabilities',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.capabilities.review.result.create',
                    'name' => 'Tạo xây dựng kế hoạch đào tạo',
                    'parent_code' => 'module_capabilities',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.capabilities.review.result.edit',
                    'name' => 'Sửa xây dựng kế hoạch đào tạo',
                    'parent_code' => 'module_capabilities',
                    'unit_permission' => 1,
                    'extend' => 'module.capabilities.review.result.index, module.capabilities.review.result.create'
                ],
                [
                    'code' => 'module.capabilities.review.result.remove',
                    'name' => 'Xóa xây dựng kế hoạch đào tạo',
                    'parent_code' => 'module_capabilities',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.capabilities.review.result.send',
                    'name' => 'Gửi kế hoạch lên đào tạo',
                    'parent_code' => 'module_capabilities',
                    'unit_permission' => 1,
                    'extend' => null
                ],
            ]
        );

        DB::table('el_permission')->insert([
            [
                'code' => 'module.capabilities.group',
                'name' => 'Xem nhóm năng lực',
                'parent_code' => 'module_capabilities',
                'extend' => null
            ],
            [
                'code' => 'module.capabilities.group.create',
                'name' => 'Thêm nhóm năng lực',
                'parent_code' => 'module_capabilities',
                'extend' => null
            ],
            [
                'code' => 'module.capabilities.group.edit',
                'name' => 'Chỉnh sửa nhóm năng lực',
                'parent_code' => 'module_capabilities',
                'extend' => 'module.capabilities.group, module.capabilities.group.create'
            ],
            [
                'code' => 'module.capabilities.group.remove',
                'name' => 'Xóa nhóm năng lực',
                'parent_code' => 'module_capabilities',
                'extend' => null
            ]
        ]);

        DB::table('el_permission')->insert([
            [
                'code' => 'module.capabilities.category',
                'name' => 'Xem danh mục năng lực',
                'parent_code' => 'module_capabilities',
                'extend' => null
            ],
            [
                'code' => 'module.capabilities.category.create',
                'name' => 'Thêm danh mục năng lực',
                'parent_code' => 'module_capabilities',
                'extend' => null
            ],
            [
                'code' => 'module.capabilities.category.edit',
                'name' => 'Chỉnh sửa danh mục năng lực',
                'parent_code' => 'module_capabilities',
                'extend' => 'module.capabilities.category, module.capabilities.category.create'
            ],
            [
                'code' => 'module.capabilities.category.remove',
                'name' => 'Xóa danh mục năng lực',
                'parent_code' => 'module_capabilities',
                'extend' => null
            ]
        ]);

        DB::table('el_permission')->insert([
            [
                'code' => 'module.capabilities.title',
                'name' => 'Xem khung năng lực theo chức danh',
                'parent_code' => 'module_capabilities',
                'extend' => null
            ],
            [
                'code' => 'module.capabilities.title.create',
                'name' => 'Thêm khung năng lực theo chức danh',
                'parent_code' => 'module_capabilities',
                'extend' => null
            ],
            [
                'code' => 'module.capabilities.title.edit',
                'name' => 'Chỉnh sửa khung năng lực theo chức danh',
                'parent_code' => 'module_capabilities',
                'extend' => 'module.capabilities.title, module.capabilities.title.create'
            ],
            [
                'code' => 'module.capabilities.title.remove',
                'name' => 'Xóa khung năng lực theo chức danh',
                'parent_code' => 'module_capabilities',
                'extend' => null
            ],
            [
                'code' => 'module.capabilities.title.course',
                'name' => 'Thêm khóa học trong khung năng lực theo chức danh',
                'parent_code' => 'module_capabilities',
                'extend' => null
            ]
        ]);

        DB::table('el_permission')->insert([
            [
                'code' => 'module.capabilities.group_percent',
                'name' => 'Quản lý nhóm phần trăm',
                'parent_code' => 'module_capabilities',
                'extend' => null
            ],
        ]);*/
    }

    public function down()
    {

    }
}

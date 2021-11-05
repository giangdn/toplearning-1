<?php

namespace Modules\API\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\API\Entities\API;

class APIDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $api = [
            [1,'iHRP_LMS_LayDMLoainghi','Loại nghỉ','https://192.168.37.16:8443/hrm/iHRP_LMS_LayDMLoainghi',1],
            [2,'iHRP_LMS_LayDMCapBacChucDanh','Cấp bậc','https://192.168.37.16:8443/hrm/iHRP_LMS_LayDMCapBacChucDanh',2],
            [3,'iHRP_LMS_LayDMChucDanh','Chức danh','https://192.168.37.16:8443/hrm/iHRP_LMS_LayDMChucDanh',3],
            [4,'iHRP_LMS_LayDMTinhThanh','Tỉnh thành','https://192.168.37.16:8443/hrm/iHRP_LMS_LayDMTinhThanh',4],
            [5,'iHRP_LMS_LayDMTrinhDo','Trình độ','https://192.168.37.16:8443/hrm/iHRP_LMS_LayDMTrinhDo',5],
            [6,'iHRP_TMS_LayDMDonVi','Đơn vị','https://192.168.37.16:8443/hrm/iHRP_TMS_LayDMDonVi',6],
            [7,'iHRP_LMS_LayThongTinNhanVien','Nhân viên','https://192.168.37.16:8443/hrm/iHRP_LMS_LayThongTinNhanVien',7],
            [8,'iHRP_LMS_LayCongTacHienHanh','Quá trình công tác','https://192.168.37.16:8443/hrm/iHRP_LMS_LayCongTacHienHanh',8],
        ];
        foreach ($api as $key => $value) {
            API::updateOrCreate(
                [
                    'id' => $value[0]
                ],
                [
                    'code'=>$value[1],
                    'name' => $value[2],
                    'url' => $value[3],
                    'order' => $value[4],
                ]
            );
        }
    }
}

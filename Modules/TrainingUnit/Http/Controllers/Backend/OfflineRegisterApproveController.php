<?php

namespace Modules\TrainingUnit\Http\Controllers\Backend;

use App\Automail;
use App\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\PermissionApproved\Entities\ApprovedModelTracking;

class OfflineRegisterApproveController extends Controller
{

    public function approve(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required'
        ], $request, ['ids' => 'Học viên', 'status' => 'Trạng thái']);

        $ids = $request->input('ids',null);
        $status = $request->input('status',0);
        $note = $request->input('note',null);
        foreach ($ids as $id) {
            $register = OfflineRegister::find($id);
            $course = OfflineCourse::findOrFail($register->course_id);
            if (empty($register)) {
                continue;
            }

            (new ApprovedModelTracking())->updateApprovedTracking(OfflineRegister::getModel(),$id,$status,$note);

            if ($status == 1) {
                $permission_code = $course->unit_id > 0 ? 'module.training_unit.offline.register.approve': 'module.offline.register.approve';
                $users = Permission::getUserPermission($permission_code, intval($course->unit_id));
                foreach ($users as $user_id){
                    $signature = getMailSignature($user_id);

                    $automail = new Automail();
                    $automail->template_code = 'approve_register';
                    $automail->params = [
                        'signature' => $signature,
                        'code' => $course->code,
                        'name' => $course->name,
                        'start_date' => $course->start_date,
                        'end_date' => $course->end_date,
                        'url' => route('module.training_unit.approve_course.course', ['id' => $course->id, 'type' => 1])
                    ];

                    $automail->users = [$user_id];
                    $automail->object_id = $course->id;
                    $automail->object_type = 'approve_offline_register';
                    $automail->addToAutomail();
                }
            }

        }

        json_message('Xét duyệt thành công');
    }
}

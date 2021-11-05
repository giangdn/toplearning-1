<?php

namespace Modules\Online\Http\Controllers\Backend;

use App\Automail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineInviteRegister;
use Modules\Online\Entities\OnlineObject;
use Modules\Online\Entities\OnlineRegister;
use Modules\PermissionApproved\Entities\ApprovedModelTracking;
use Modules\User\Entities\TrainingProcess;

class OnlineRegisterController extends Controller
{
    public function approve(Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1',
        ], $request, [
            'ids' => 'Học viên',
            'status' => 'Trạng thái'
        ]);


        $ids = $request->input('ids', null);
        $status = $request->input('status', null);
        $note = $request->input('note', null);
        $model = $request->input('model');
        foreach ($ids as $id) {
            (new ApprovedModelTracking())->updateApprovedTracking(OnlineRegister::getModel(),$id,$status,$note);

            $onlineRegister = OnlineRegister::where('id', '=', $id)->select('user_id','course_id')->first();
            $user_id = $onlineRegister->user_id;
            $course_id = $onlineRegister->course_id;
            TrainingProcess::where(['user_id'=>$user_id,'course_id'=>$course_id,'course_type'=>1])->update(['status'=>$status]);
        }
        $course = OnlineCourse::find($course_id);
        $users = OnlineRegister::whereIn('id', $ids)->get();
        if ($status == 1 ) {
            foreach ($users as $index => $user) {
                $signature = getMailSignature($user->user_id);

                $automail = new Automail();
                $automail->template_code = 'registered_course';
                $automail->params = [
                    'signature' => $signature,
                    'gender' => $user->user->gender=='1'?'Anh':'Chị',
                    'full_name' => $user->user->full_name,
                    'course_code' => $course->code,
                    'course_name' => $course->name,
                    'course_type' => 'Online',
                    'start_date' => $course->start_date,
                    'end_date' => $course->end_date,
                    'training_location' => 'Elearning',
                    'url' => route('module.online.detail_online', ['id' => $course->id])
                ];
                $automail->users = [$user->user_id];
                $automail->check_exists = true;
                $automail->check_exists_status = 0;
                $automail->object_id = $course->id;
                $automail->object_type = 'register_approved_online';
                $automail->addToAutomail();
            }
        }
        else{
            foreach ($users as $user) {
                $signature = getMailSignature($user->user_id);
                $params = [
                    'signature' => $signature,
                    'Gender' => $user->user->gender=='1'?'Anh':'Chị',
                    'FirstName' => $user->user->full_name,
                    'courseCode' => $course->code,
                    'courseName' => $course->name
                ];
                $user_id = [$user->user_id];
                $this->saveEmailDeniedRegister($params,$user_id,$user->id);
            }
        }

        if($status == 0) {
            json_message('Đã từ chối','success');
        } else {
            json_message('Duyệt thành công','success');
        }
    }
    public function saveEmailDeniedRegister(array $params,array $user_id,int $register_id)
    {
        $automail = new Automail();
        $automail->template_code = 'declined_enroll';
        $automail->params = $params;
        $automail->users = $user_id;
        $automail->check_exists = true;
        $automail->check_exists_status = 0;
        $automail->object_id = $register_id;
        $automail->object_type = 'declined_enroll_online';
        $automail->addToAutomail();
    }
}

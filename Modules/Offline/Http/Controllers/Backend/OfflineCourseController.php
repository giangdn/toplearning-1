<?php

namespace Modules\Offline\Http\Controllers\Backend;

use App\Automail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineInviteRegister;
use Modules\Offline\Entities\OfflineObject;
use Modules\Online\Entities\OnlineObject;
use Modules\PermissionApproved\Entities\ApprovedModelTracking;

class OfflineCourseController extends Controller
{
    public function approve(Request $request)
    {
        $user_invited = null;
        $check_user_invited = OfflineInviteRegister::query()->where('user_id', '=', Auth::id());
        if ($check_user_invited->exists()){
            $user_invited = $check_user_invited->pluck('course_id')->toArray();
        }
        $note = $request->input('note', null);
        $ids = $request->input('ids', null);
        $status = $request->input('status', null);
        foreach ($ids as $id) {
            if ($user_invited && in_array($id, $user_invited)){
                continue;
            }
            (new ApprovedModelTracking())->updateApprovedTracking(OfflineCourse::getModel(),$id,$status,$note);

            $this->updateEmailCourseObject($id);
        }

        if($status == 0) {
            json_message('Đã từ chối','success');
        } else {
            json_message('Duyệt thành công','success');
        }
    }
    public function updateEmailCourseObject($course_id)
    {
        $course = OfflineCourse::find($course_id);
        // theo đơn vị
        $objects_unit = OfflineObject::select('id','course_id','unit_id','title_id')->where('course_id',$course_id)
            ->with('unit:id,code',
                'unit.profiles:unit_code,id,code,user_id,email,firstname,lastname,gender')->has('unit')->get();
        foreach ($objects_unit as $object) {
            foreach ($object->unit['profiles'] as $profile) {
                $signature = getMailSignature($profile->user_id);
                $params = [
                    'gender' => $profile->gender=='1'?'Anh':'Chị',
                    'full_name' => $profile->full_name,
                    'course_code' => $course->code,
                    'course_name' => $course->name,
                    'course_type' => 'Offline',
                    'start_date' => get_date($course->start_date),
                    'end_date' => get_date($course->end_date),
                    'training_location' => 'Elearning',
                    'url' => route('module.offline.detail', ['id' => $course->id]),
                    'signature' => $signature,
                ];
                $user_id = [$profile->user_id];
                $this->saveEmailCourseObject($params,$user_id,$course->id);
            }
        }
        //theo chức danh
        $objects = OfflineObject::select('id','course_id','unit_id','title_id')->where('course_id',$course_id)
            ->with('titles:id,code',
                'titles.profiles:title_code,id,code,user_id,email,firstname,lastname,gender')->whereNotNull('title_id')->get();
        foreach ($objects as $object) {
            foreach ($object->titles as $profiles) {
                foreach ($profiles->profiles as $profile){
                    $signature = getMailSignature($profile->user_id);
                    $params = [
                        'gender' => $profile->gender=='1'?'Anh':'Chị',
                        'full_name' => $profile->full_name,
                        'course_code' => $course->code,
                        'course_name' => $course->name,
                        'course_type' => 'Offline',
                        'start_date' => get_date($course->start_date),
                        'end_date' => get_date($course->end_date),
                        'training_location' => 'Elearning',
                        'url' => route('module.offline.detail', ['id' => $course->id]),
                        'signature' => $signature,
                    ];
                    $user_id = [$profile->user_id];
                    $this->saveEmailCourseObject($params,$user_id,$course->id);
                }
            }
        }
    }

    public function saveEmailCourseObject(array $params,array $user_id,int $course_id)
    {
        $automail = new Automail();
        $automail->template_code = 'register_course_object';
        $automail->params = $params;
        $automail->users = $user_id;
        $automail->check_exists = true;
        $automail->check_exists_status = 0;
        $automail->object_id = $course_id;
        $automail->object_type = 'register_course_offline_object';
        $automail->addToAutomail();
    }
}

<?php
namespace Modules\Offline\Imports;

use App\Models\Categories\Subject;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\PermissionTypeUnit;
use App\UserPermissionType;
use Illuminate\Support\Facades\Auth;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineInviteRegister;
use Modules\Offline\Entities\OfflineObject;
use Modules\Offline\Entities\OfflineRegister;
use App\Profile;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\User\Entities\TrainingProcess;

class RegisterImport implements ToModel, WithStartRow
{
    public $errors;
    public $course_id;

    public function __construct($course_id)
    {
        $this->errors = [];
        $this->course_id = $course_id;
    }

    public function model(array $row)
    {
        $error = false;
        $user_code = (string) $row[1];

        $profile = Profile::where('code', '=', $user_code)->first();

        $user_invited = false;
        $check_user_invited = OfflineInviteRegister::query()
            ->where('course_id', '=', $this->course_id)
            ->where('user_id', '=', Auth::id());
        if ($check_user_invited->exists()){
            $user_invited = true;
            $num_register = $check_user_invited->first()->num_register;

            $user_permission_type = UserPermissionType::query()
                ->select(['permission_type_id'])
                ->whereUserId(Auth::id())
                ->groupBy('permission_type_id')
                ->first();
            $condition=PermissionTypeUnit::conditionUnitGroup(@$user_permission_type->permission_type_id);
            $arr_user_code = Profile::query()
                ->whereExists(function ($queryExists) use ($condition){
                    $queryExists->select('id')
                        ->from('el_unit_view')
                        ->whereColumn(['id'=>'unit_id']);
                    if ($condition)
                        $queryExists->whereRaw($condition);
                    else
                        $queryExists->whereRaw("1=-1");
                })->pluck('code')->toArray();

            if (!in_array($user_code, $arr_user_code)){
                $this->errors[] = 'Nhân viên có mã <b>'. $user_code .'</b> không thuộc đơn vị bạn quản lý';
                $error = true;
            }
        }

        if(isset($profile)){
            $title = Titles::where('code', '=', $profile->title_code)->first();
            $unit = Unit::where('code', '=', $profile->unit_code)->first();

            $fullname = $profile->lastname . ' ' . $profile->firstname;
            $course_object_title = OfflineObject::where('course_id', '=', $this->course_id)->whereNotNull('title_id')->pluck('title_id')->toArray();

            $course_object_unit = OfflineObject::where('course_id', '=', $this->course_id)->whereNotNull('unit_id')->pluck('unit_id')->toArray();

            if (count($course_object_title) > 0){
                if (!in_array($title->id, $course_object_title)){
                    $this->errors[] = 'Chức danh của <b>'. $fullname .'</b> không thể đăng kí khóa học';
                    $error = true;
                }
            }

            if (count($course_object_unit) > 0){
                if (!in_array($unit->id, $course_object_unit)){
                    $this->errors[] = 'Đơn vị của <b>'. $fullname .'</b> không thể đăng kí khóa học';
                    $error = true;
                }
            }

            $register = OfflineRegister::where('user_id', '=', $profile->user_id)
            ->where('course_id', '=', $this->course_id)->first();

            if ($register) {
                $this->errors[] = 'Mã nhân viên <b>'. $row[1] .'</b> đã đăng kí khóa học';
                $error = true;
            }
        }

        if (empty($profile)) {
            $this->errors[] = 'Mã nhân viên <b>'. $row[1] .'</b> không tồn tại';
            $error = true;
        }

        if ($user_invited){
            if ($num_register == 0){
                $this->errors[] = 'Đã đủ SL. Mã nhân viên <b>'. $row[1] .'</b> không thể đăng kí khóa học';
                $error = true;
            }else{
                $num_register -= 1;

                OfflineInviteRegister::query()
                    ->where('course_id', '=', $this->course_id)
                    ->where('user_id', '=', Auth::id())
                    ->update([
                        'num_register' => $num_register
                    ]);
            }
        }

        if($error) {
            return null;
        }

        OfflineRegister::create([
            'user_id' =>(int) $profile->user_id,
            'course_id' => $this->course_id,
        ]);
        $offline_course = OfflineCourse::find($this->course_id);
        $subject = Subject::find($offline_course->subject_id);
        $_profile = \DB::table('el_profile_view')->where('user_id','=',$profile->user_id)->first();
        TrainingProcess::create([
            'user_id'=>(int) $profile->user_id,
            'course_id'=>$this->course_id,
            'course_code'=>$offline_course->code,
            'course_name'=>$offline_course->name,
            'course_type'=>2,
            'subject_id'=>$subject->id,
            'subject_code'=>$subject->code,
            'subject_name'=>$subject->name,
            'titles_code'=>$_profile->title_code,
            'titles_name'=>$_profile->title_name,
            'unit_code'=>$_profile->unit_code,
            'unit_name'=>$_profile->unit_name,
            'start_date'=>$offline_course->start_date,
            'end_date'=>$offline_course->end_date,
            'process_type'=>1,
            'certificate'=>$offline_course->cert_code,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

}

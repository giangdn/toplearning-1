<?php
namespace Modules\Online\Imports;

use App\Models\Categories\Subject;
use App\PermissionTypeUnit;
use App\Profile;
use App\Models\Categories\Titles;
use App\UserPermissionType;
use Illuminate\Support\Facades\Auth;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineInviteRegister;
use Modules\Online\Entities\OnlineObject;
use Modules\Online\Entities\OnlineRegister;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
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
        $user_code = $row[1];

        $profile = Profile::where('code', '=', $user_code)->first();

        $user_invited = false;
        $check_user_invited = OnlineInviteRegister::query()
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
            $fullname = $profile->lastname . ' ' . $profile->firstname;
            $course_object = OnlineObject::where('course_id', '=', $this->course_id)->pluck('title_id')->toArray();

            if (!in_array($title->id, $course_object)){
                $this->errors[] = 'Chức danh của <b>'. $fullname .'</b> không thể đăng kí khóa học';
                $error = true;
            }

            $register = OnlineRegister::where('user_id', '=', $profile->user_id)
                ->where('user_type', '=', 1)
                ->where('course_id', '=', $this->course_id)
                ->first();

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

                OnlineInviteRegister::query()
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

        $course = OnlineCourse::findOrFail($this->course_id);

        OnlineRegister::create([
            'user_id' =>(int) $profile->user_id,
            'course_id' => $this->course_id,
            'user_type' => 1
        ]);
        $model = OnlineRegister::orderBy('id', 'DESC')->first();
        if ($course->auto == 1){
            $model->status = 1;

            $quizs = Quiz::where('course_id', '=', $this->course_id)
                ->where('status', '=', 1)->get();
            if ($quizs){
                foreach ($quizs as $quiz){
                    $quiz_part = QuizPart::where('quiz_id', '=', $quiz->id)->first();
                    if ($quiz_part){
                        QuizRegister::query()
                            ->updateOrCreate([
                                'quiz_id' => $quiz->id,
                                'user_id' => $model->user_id,
                                'type' => 1,
                            ],[
                                'part_id' => $quiz_part->id,
                            ]);
                    }else{
                        continue;
                    }
                }
            }
            $model->save();
        }
        // update training process
        $offline_course = OnlineCourse::find($this->course_id);
        $subject = Subject::find($offline_course->subject_id);
        $_profile = \DB::table('el_profile_view')->where('user_id','=',$profile->user_id)->first();
        TrainingProcess::create([
            'user_id'=>(int) $profile->user_id,
            'user_type' => 1,
            'course_id'=>$this->course_id,
            'course_code'=>$offline_course->code,
            'course_name'=>$offline_course->name,
            'course_type'=>1,
            'subject_id'=>$subject->id,
            'subject_code'=>$subject->code,
            'subject_name'=>$subject->name,
            'titles_code'=>$_profile->titles_code,
            'titles_name'=>$_profile->titles_name,
            'unit_code'=>$_profile->unit_code,
            'unit_name'=>$_profile->unit_name,
            'start_date'=>$offline_course->start_date,
            'end_date'=>$offline_course->end_date,
            'process_type'=>1,
            'certificate'=>$course->cert_code,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

}

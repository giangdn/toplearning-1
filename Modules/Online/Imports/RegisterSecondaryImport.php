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
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\User\Entities\TrainingProcess;

class RegisterSecondaryImport implements ToModel, WithStartRow
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

        $profile = QuizUserSecondary::where('code', '=', $user_code)->first();
        if(isset($profile)){
            $register = OnlineRegister::where('user_id', '=', $profile->id)
                ->where('user_type', '=', 2)
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

        if($error) {
            return null;
        }

        OnlineRegister::create([
            'user_id' =>(int) $profile->id,
            'course_id' => $this->course_id,
            'user_type' => 2,
            'status' => 1,
        ]);

        $model = OnlineRegister::orderBy('id', 'DESC')->first();
        $quizs = Quiz::where('course_id', '=', $this->course_id)->where('status', '=', 1)->get();
        if ($quizs){
            foreach ($quizs as $quiz){
                $quiz_part = QuizPart::where('quiz_id', '=', $quiz->id)->first();
                if ($quiz_part){
                    QuizRegister::query()
                        ->updateOrCreate([
                            'quiz_id' => $quiz->id,
                            'user_id' => $model->user_id,
                            'type' => 2,
                        ],[
                            'part_id' => $quiz_part->id,
                        ]);
                }else{
                    continue;
                }
            }
        }

        // update training process
        $course = OnlineCourse::find($this->course_id);
        $subject = Subject::find($course->subject_id);

        TrainingProcess::create([
            'user_id' => (int) $profile->id,
            'user_type' => 2,
            'course_id' => $this->course_id,
            'course_code' => $course->code,
            'course_name' => $course->name,
            'course_type' => 1,
            'subject_id' => $subject->id,
            'subject_code' => $subject->code,
            'subject_name' => $subject->name,
            'start_date' => $course->start_date,
            'end_date' => $course->end_date,
            'process_type' => 1,
            'certificate' => $course->cert_code,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

}

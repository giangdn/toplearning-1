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
use App\ProfileView;
use Modules\Offline\Entities\OfflineSchedule;
use App\Models\Categories\Absent;
use App\Models\Categories\AbsentReason;
use App\Models\Categories\Discipline;
use Modules\Offline\Entities\OfflineAttendance;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\User\Entities\TrainingProcess;

class AttendanceImport implements ToModel, WithStartRow
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
        if (empty($row[1])){
            return;
        }

        $error = false;
        $user_code = strval($row[1]);
        $lesson_date = date("Y-m-d", strtotime(trim($row[7])));
        $schedule_time = explode('=>',trim($row[8]));
        $start_time = $schedule_time[0];
        $end_time = $schedule_time[1];
        $percent = $row[9];
        $note = $row[10];
        $discipline_name = $row[11];
        $absent_name = $row[12];
        $absent_reason_name = $row[13];

        $profile = ProfileView::where('code', '=', $user_code)->first();
        if (empty($profile)){
            $this->errors[] = 'Mã nhân viên <b>'. $user_code .'</b> không tồn tại';
            $error = true;
        }
        // dd($profile->user_id);
        $check_register = OfflineRegister::where('user_id', @$profile->user_id)->where('status',1)->where('course_id',$this->course_id)->first();
        if (empty($check_register)) {
            $this->errors[] = 'Mã nhân viên <b>'. $row[1] .'</b> chưa ghi danh';
            $error = true;
        }

        $check_time_schedule = OfflineSchedule::where('lesson_date',$lesson_date)->where('start_time',$start_time)->where('end_time',$end_time)->where('course_id',$this->course_id)->first();
        if (empty($check_time_schedule)) {
            $this->errors[] = 'Buổi học dòng số: '. $row[0] .' không tồn tại';
            $error = true;
        }

        if(isset($discipline_name)) {
            $check_discipline = Discipline::where('name', $discipline_name)->first();
            if (empty($check_discipline)) {
                $this->errors[] = 'Vi phạm dòng số: '. $row[0] .' không đúng';
                $error = true;
            }
        }

        if(isset($absent_name)) {
            $check_absent = Absent::where('name', $absent_name)->first();
            if (empty($check_absent)) {
                $this->errors[] = 'Loại nghỉ dòng số: '. $row[0] .' không đúng';
                $error = true;
            }
        }

        if(isset($absent_reason_name)) {
            $check_absent_reason = AbsentReason::where('name', $absent_reason_name)->first();
            if (empty($check_absent_reason)) {
                $this->errors[] = 'Lý do vắng mặt dòng số: '. $row[0] .' không đúng';
                $error = true;
            }
        }

        if($error) {
            return null;
        }

        OfflineAttendance::query()->updateOrCreate([
            'user_id' => $profile->user_id,
            'course_id' => $this->course_id,
            'schedule_id' => $check_time_schedule->id,
        ],[
            'course_id' => $this->course_id,
            'user_id' => $profile->user_id,
            'schedule_id' => $check_time_schedule->id,
            'register_id' => $check_register->id,
            'percent' => $percent,
            'status' => $percent ? '1' : '0',
            'absent_reason_id' => isset($absent_reason_name) && !empty($check_absent_reason) ? $check_absent_reason->id : '0',
            'discipline_id' => isset($discipline_name) && !empty($check_discipline) ? $check_discipline->id : '0',
            'absent_id' => isset($absent_name) && !empty($check_absent) ? $check_absent->id : '0',
            'note' => $note,
        ]);

    }

    public function startRow(): int
    {
        return 3;
    }

}

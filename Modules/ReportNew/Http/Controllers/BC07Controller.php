<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\Categories\Area;
use App\Models\Categories\Position;
use App\Models\Categories\TrainingType;
use App\Models\Categories\Unit;
use App\Profile;
use Illuminate\Http\Request;
use Modules\CourseOld\Entities\CourseOld;
use Modules\Indemnify\Entities\Indemnify;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineStudentCost;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseCost;
use Modules\ReportNew\Entities\BC07;
use App\Models\Categories\TrainingPartner;

class BC07Controller extends ReportNewController
{
    public function review(Request $request, $key)
    {
        $get_name_url = explode('/',url()->current());
        $get_menu_child = get_menu_child($get_name_url[4]);

        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        $report = parent::reportList();
        return view('reportnew::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'level_name' => $level_name,
            'get_menu_child' => $get_menu_child,
            'name_url' => $get_name_url[4],
        ]);
    }

    public function getData(Request $request)
    {
        $units = Unit::where('status', '=', 1)->get();
        $training_partners = TrainingPartner::get();

        $user_id = $request->user_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $unit_id = $request->unit_id;
        $area_id = $request->area;
        if (!$user_id && !$unit_id){
            json_result([]);
        }

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC07::sql($user_id, $from_date, $to_date, $unit_id, $area_id);
        $count = $query->count();
        $query->orderBy('el_training_process.'.$sort, 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $profile = Profile::query()->find($row->user_id);
            $position = Position::query()->find($profile->position_id);
            $title = @$profile->titles;
            $unit_1 = @$profile->unit;
            $unit_2 = @$unit_1->parent;
            $area = Area::find(@$unit_1->area_id);

            $row->user_code = $profile->code;
            $row->fullname = $profile->getFullName();
            $row->email = $profile->email;
            $row->phone = $profile->phone;

            if ($row->course_old == 1){
                $courseOld = CourseOld::whereCourseCode($row->course_code)->whereUserCode($profile->code)->where('start_date', $row->start_date)->first();
                $data_course_old = $courseOld ? json_decode($courseOld->data, true) : [];

                $row->area = $courseOld ? $data_course_old['Khu vực'] : '';
                $row->unit_name_1 = $courseOld ? $data_course_old['Đơn vị trực tiếp'] : '';
                $row->unit_name_2 = $courseOld ? $data_course_old['Đơn vị quản lý'] : '';
                $row->position_name = $courseOld ? $data_course_old['Chức vụ'] : '';
                $row->title_name = $courseOld ? $data_course_old['Chức danh'] : '';
                $row->training_unit = $courseOld ? $data_course_old['Đơn vị đào tạo'] : '';
                $row->process_type = $courseOld ? ($data_course_old['Hình thức đào tạo'] == 1 ? 'Đào tạo trực tuyến' : 'Đào tạo tập trung') : '';
                $row->course_time = $courseOld ? $data_course_old['Thời lượng khóa học'] : '';
                $row->attendance = $courseOld ? $data_course_old['Tổng thời lượng tham gia'] : '';
                $row->start_date = $courseOld ? $data_course_old['Từ ngày'] : '';
                $row->end_date = $courseOld ? $data_course_old['Đến ngày'] : '';
                $row->time_schedule = $courseOld ? $data_course_old['Thời gian'] : '';
                $row->course_cost = $courseOld ? number_format($data_course_old['Bình quân CP Học viên']) : '';
                $row->score = $courseOld ? $data_course_old['Điểm'] : '';
                $row->result = $courseOld ? $data_course_old['Kết quả'] : '';
            }else{
                $row->area = @$area->name;
                $row->unit_name_1 = @$unit_1->name;
                $row->unit_name_2 = @$unit_2->name;
                $row->position_name = @$position->name;
                $row->title_name = @$title->name;
                if ($row->course_type == 2){
                    $course = OfflineCourse::query()->find($row->course_id);
                    if (!$course)
                        continue;
                    $unit_name = [];
                    !empty($course->training_unit) ? $training_unit = json_decode($course->training_unit) : $training_unit = [];
                    if($course->training_unit_type == 0 && !empty($training_unit)) {
                        foreach ($units as $key => $unit) {
                            if(in_array($unit->id, $training_unit)) {
                                $unit_name[] = $unit->name;
                            }
                        }
                    } else if ($course->training_unit_type == 1 && !empty($training_unit)) {
                        foreach ($training_partners as $key => $training_partner) {
                            if(in_array($training_partner->id, $training_unit)) {
                                $unit_name[] = $training_partner->name;
                            }
                        }
                    }
                    $row->training_unit = !empty($unit_name) ? implode(',',$unit_name) : '';
                    $row->course_time = $course->course_time;
                    $row->process_type = 'Đào tạo tập trung';

                    $register = OfflineRegister::whereCourseId($row->course_id)
                        ->where('user_id', '=', $row->user_id)
                        ->first();
                    if (!$register)
                        continue;
                    $schedules = OfflineSchedule::query()
                        ->select(['a.end_time', 'a.lesson_date'])
                        ->from('el_offline_schedule as a')
                        ->where('a.course_id', '=', $row->course_id)
                        ->get();
                    foreach ($schedules as $schedule){
                        if (get_date($schedule->end_time, 'H:i:s') <= '12:00:00'){
                            $row->time_schedule .= 'Sáng '. get_date($schedule->lesson_date) .'; ';
                        }else{
                            $row->time_schedule .= 'Chiều '. get_date($schedule->lesson_date) .'; ';
                        }
                    }

                    $row->attendance = OfflineAttendance::query()->where('register_id', '=', $register->id)->count();

                    $indemnify = Indemnify::whereCourseId($row->course_id)->whereUserId($row->user_id)->first();
                    $student_cost = OfflineStudentCost::getTotalStudentCost($register->id);
                    $course_cost = ($indemnify ? $indemnify->commit_amount : 0) + $student_cost;
                    $row->course_cost = number_format($course_cost, 2);
                }else{
                    $course = OnlineCourse::query()->find($row->course_id);
                    if (!$course)
                        continue;
                    $row->course_cost = '';
                    $row->course_time = preg_replace("/[^0-9]/", '', $course->course_time);
                    $row->process_type = 'Đào tạo trực tuyến';
                }
                $row->start_date = get_date($course->start_date);
                $row->end_date = get_date($course->end_date);
                $row->result = $row->pass == 1 ? 'Đạt' : 'Không đạt';
                $row->score = $row->mark;
            }
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}

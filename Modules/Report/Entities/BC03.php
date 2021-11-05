<?php

namespace Modules\Report\Entities;

use App\Models\Categories\TrainingForm;
use Illuminate\Database\Eloquent\Model;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseComplete;
use Modules\Offline\Entities\OfflineObject;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineTeacher;

class BC03 extends Model
{

    public static function getCourses($fromDate, $toDate)
    {
        $fromDate = date_convert($fromDate);
        $toDate = date_convert($toDate,'23:59:59');

        $query = OfflineCourse::query()
            ->where('status', '=', 1)
            ->where(function ($where) use ($fromDate,$toDate){
                $where->where('start_date', '>=', $fromDate);
                $where->Where('end_date', '<=', $toDate);
            })
            ->select(
                \DB::raw("id, code as course_code, name as course_name, N'Tập trung' AS course_type, course_time, start_date, end_date, training_form_id, training_unit, cost_class, null as teacher, null as students, null as attended, null as percent_attended, null as unattended, null percent_unattended, null complete, null percent_complete, null uncomplete, null percent_uncomplete, null object")
            );
        return $query;
    }

    /*Xuất báo cáo*/
    public static function getData($fromDate,$toDate)
    {
        $data = array();
        $courses = self::getCourses($fromDate, $toDate)->get();
        foreach ($courses as $index => $item) {
            $row = [];
            $students = null;
            $attended = null;
            $complete = null;

            $row['STT'] = ($index+1);

            foreach (array_keys($item->original) as $i => $e) {
                if ($e == 'id') continue;
                if ($e == 'students'){
                    $students = OfflineRegister::countRegisters($item->id);
                    $row[$e] = $students;
                }elseif ($e == 'course_time'){
                    $course_time = preg_replace("/[^0-9]./", '', $item->{$e});
                    $course_time_unit = preg_replace("/[^a-z]/", '', $item->{$e});

                    switch ($course_time_unit){
                        case 'day': $time_unit = 'Ngày'; break;
                        case 'session': $time_unit = 'Buổi'; break;
                        default : $time_unit = 'Giờ'; break;
                    }
                    $row[$e] = $course_time ? $course_time . ' ' . $time_unit : '';
                }elseif($e == 'start_date' || $e == 'end_date')
                    $row[$e] = get_date($item->{$e});
                elseif ($e == 'training_form_id'){
                    $training_form = TrainingForm::find($item->{$e});
                    $row[$e] = (isset($training_form) ? $training_form->name : '');
                }
                elseif ($e == 'cost_class')
                    $row[$e] = number_format($item->{$e},0,',','.');
                elseif ($e == 'teacher'){
                    $row[$e] = OfflineTeacher::getTeachers($item->id);
                }elseif ($e == 'attended'){
                    $attended = OfflineAttendance::countAttendance($item->id);
                    $row[$e] = $attended;
                }elseif ($e == 'percent_attended'){
                    $row[$e] = $students ? number_format($attended/$students*100,2) : null;
                }elseif ($e == 'unattended'){
                    $row[$e] = $students - $attended;
                }elseif ($e == 'percent_unattended'){
                    $row[$e] = $students ? number_format(($students - $attended)/$students*100,2) : null;
                }elseif ($e == 'complete'){
                    $complete = OfflineCourseComplete::countCourseComplete($item->id);
                    $row[$e] = $complete;
                }elseif ($e == 'percent_complete'){
                    $row[$e] = $students ? number_format($complete/$students*100,2) : null;
                }elseif ($e == 'uncomplete'){
                    $row[$e] = $students-$complete;
                }elseif ($e == 'percent_uncomplete'){
                    $row[$e] = $students ? number_format(($students - $complete)/$students*100,2) : null;
                }elseif ($e == 'object'){
                    $row[$e] = OfflineObject::getObjects($item->id);
                }
                else
                    $row[$e] = $item->{$e};
            }
            $data[] = $row;
        }
        return $data;
    }
}

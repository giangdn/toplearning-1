<?php

namespace Modules\Report\Http\Controllers;

use App\Profile;
use App\Models\Categories\TrainingProgram;
use Illuminate\Http\Request;
use Modules\Report\Entities\BC23;

class BC23Controller extends ReportController
{
    public function review(Request $request, $key)
    {
        $training_program_id = $request->training_program;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        if ($training_program_id){
            $course = \DB::query()
                ->select('id', 'course_type', 'name')
                ->from('el_course_view')
                ->where('status', '=', 1)
                ->where('training_program_id', '=', $training_program_id);
                if ($from_date && $to_date){
                    $course = $course->where('start_date','>=', date_convert($from_date))
                        ->where('start_date','<=', date_convert($to_date,'23:59:59'));
                };
            $course = $course->get();

            $sort = $request->input('sort', 'user_id');
            $order = $request->input('order', 'asc');
            $offset = $request->input('offset', 0);

            $query = BC23::sql($training_program_id, $from_date, $to_date);
            $query->orderBy('course_register.'.$sort, $order);
            $query->offset($offset);
            $rows = $query->get();
        }

        $user = function ($user_id){
            $users = Profile::query()
                ->select([
                    'a.user_id',
                    'a.code',
                    'a.lastname',
                    'a.firstname',
                    'b.name AS title_name',
                    'c.name AS unit_name'
                ])
                ->from('el_profile as a')
                ->leftJoin('el_titles as b', 'b.code', '=', 'a.title_code')
                ->leftJoin('el_unit as c', 'c.code', '=', 'a.unit_code')
                ->where('a.user_id', '=', $user_id)
                ->first();
            return $users;
        };

        $score = function ($user_id, $course_id, $course_type){
            $query = \DB::query()
                ->select(['score'])
                ->from('el_course_register_view')
                ->where('user_id', '=', $user_id)
                ->where('course_id', '=', $course_id)
                ->where('course_type', '=', $course_type)
                ->first();

            return $query;
        };

        $training_programs = TrainingProgram::where('status', '=', 1)->get();
        $report = parent::reportList();
        return view('report::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'training_programs' => $training_programs,
            'rows' => $training_program_id ? $rows : '',
            'course' => $training_program_id ? $course : '',
            'training_program_id' => $training_program_id,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'user' => $user,
            'score' => $score
        ]);
    }
}

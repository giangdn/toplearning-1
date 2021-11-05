<?php

namespace Modules\Report\Http\Controllers;

use App\Permission;
use App\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use function PHPSTORM_META\elementType;

use Modules\Report\Entities\BC16;
class BC16Controller extends ReportController
{
    public function review(Request $request, $key)
    {
        $units =  UnitManager::getArrayUnitManagedByUser();
        $quiz = Quiz::where('status', '=', 1);
        if (Permission::isUnitManager()){
            $quiz->whereIn('unit_id', $units);
        }
        $quiz = $quiz->get();

        $report = parent::reportList();
        return view('report::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'quiz' => $quiz,
        ]);
    }

    public function getData(Request $request)
    {
        if (!$request->quiz_id && !$request->from_date && !$request->to_date)
            json_result([]);

        $quiz_id = $request->quiz_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $sort = $request->input('sort', 'a.id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC16::sql($quiz_id, $from_date, $to_date);
        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $part = QuizPart::where('quiz_id', '=', $quiz_id)->where('id', '=', $row->part_id)->first();

            $row->full_name = $row->lastname .' '. $row->firstname;
            $row->part = $part->name;

            $status = '';
            switch ($row->state) {
                case 'inprogress': $status = 'Đang làm bài'; break;
                case 'completed': $status = 'Hoàn thành'; break;
            }

            $row->start_date = date('H:i:s d/m/Y', $row->timestart);
            $row->end_date = $row->timefinish > 0 ? date('H:i:s d/m/Y', $row->timefinish) : '';
            $row->status = $status;
            $row->execution_time = $row->timefinish > 0 ? calculate_time_span($row->timestart, $row->timefinish) : '';

        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
}

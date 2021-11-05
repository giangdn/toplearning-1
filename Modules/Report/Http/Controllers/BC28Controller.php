<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Survey\Entities\Survey;

class BC28Controller extends ReportController
{
    public function review(Request $request, $key)
    {
        $survey = Survey::where('status', '=', 1)->get();
        $report = parent::reportList();
        return view('report::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'survey' => $survey,
        ]);
    }
}

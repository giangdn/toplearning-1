<?php

namespace Modules\ReportNew\Entities;

use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;

class BC28 extends Model
{
    public static function sql($from_date, $to_date, $quiz_id)
    {
        $from_date = date_convert($from_date);
        $to_date = date_convert($to_date,'23:59:59');

        $part = QuizPart::whereQuizId($quiz_id)
            ->where('start_date', '>=', $from_date)
            ->where('start_date', '<=', $to_date)
            ->first();

        QuizRegister::addGlobalScope(new CompanyScope());
        $query = QuizRegister::query();
        $query->where('quiz_id', '=', $quiz_id);
        $query->where('quiz_id', '=', @$part->quiz_id);
        /*$query->where('type', '=',1);*/

        return $query;
    }

}

<?php

namespace Modules\ReportNew\Entities;

use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;

class BC02 extends Model
{
    public static function sql($from_date, $to_date, $type_id, $role_id, $quiz_id)
    {
        $dbprefix = \DB::getTablePrefix();
        $from_date = date_convert($from_date);
        $to_date = date_convert($to_date,'23:59:59');

        QuizAttempts::addGlobalScope(new DraftScope());
        $query = QuizAttempts::query();
        $query->select([
            'el_quiz_attempts.*',
            'b.type_id'
        ])
            ->leftJoin('el_quiz AS b', 'b.id', '=', 'el_quiz_attempts.quiz_id');

        if ($quiz_id){
            $query->where('b.id', '=', $quiz_id);
        }

        if ($from_date) {
            $query->where(\DB::raw('(select MIN(start_date)
            from '.$dbprefix.'el_quiz_part
            where quiz_id = '.$dbprefix.'b.id)'), '>=', $from_date);
        }

        if ($to_date) {
            $query->where(\DB::raw('(select MIN(start_date)
            from '.$dbprefix.'el_quiz_part
            where quiz_id = '.$dbprefix.'b.id)'), '<=', $to_date);
        }

        if ($type_id){
            $query->where('b.type_id', '=', $type_id);
        }
        if ($role_id){
            $query->whereIn('b.created_by', function ($sub) use ($role_id){
                $sub->select(['a.user_id'])
                    ->from('el_user_permission_type as a')
                    ->leftJoin('el_permission_type_unit as b', 'b.permission_type_id', '=', 'a.permission_type_id')
                    ->leftJoin('el_permissions as c', 'c.id', '=', 'a.permission_id')
                    ->leftJoin('el_role_permission_type as d', 'c.permission_id', '=', 'a.permission_id')
                    ->whereIn('c.name', function ($sub2){
                        $sub2->select(['per.parent'])
                            ->from('el_model_has_permissions as model')
                            ->leftJoin('el_permissions as per', 'per.id', '=', 'model.permission_id')
                            ->whereColumn('model.model_id', '=', 'a.user_id')
                            ->where('per.name', '=', 'quiz-create');
                    })
                    ->where('c.name', '=', 'quiz')
                    ->where('d.role_id', '=', $role_id)
                    ->pluck('a.user_id')->toArray();
            });
        }

        return $query;
    }

}

<?php

namespace Modules\Report\Entities;

use App\Profile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class BC35 extends Model
{
    public static function getQuery() {
        $query = Profile::query();
        $query->select([
            'profile.user_id',
            'profile.code',
            \DB::raw('CONCAT(lastname, \' \', firstname) AS fullname'),
            'profile.level',
            'profile.join_company',
            'profile.status',
            'unit.level AS unit_level',
            'unit.name AS unit_name',
            'unit.id AS unit_id',
            'unit.parent_code AS unit_parent',
            'title.name AS title_name',
            'subject.name AS subject_name',
            'course.name AS course_name',
            'course_child.name AS course_child_name',
            'result.date_complete',
            'result.score',
            'teacher.code AS teacher_code',
            'teacher.name AS teacher_name',
            'result.updated_by',
            'result.created_at'
        ]);
    
        $query->from('el_profile AS profile')
            ->join('el_unit AS unit', 'unit.code', '=', 'profile.unit_code')
            ->join('el_titles AS title', 'profile.title_code', '=', 'title.code')
            ->join('el_manager_course_register AS register', 'register.user_id', '=', 'profile.user_id')
            ->join('el_manager_course AS course', 'course.id', '=', 'register.manager_course_id')
            ->join('el_manager_course_child AS course_child', 'course_child.manager_course_id', '=', 'course.id')
            ->join('el_subject AS subject', 'subject.id', '=', 'course.subject_id')
            ->leftJoin('el_training_teacher AS teacher', 'teacher.id', '=', 'course_child.teacher_id')
            ->leftJoin('el_manager_course_result AS result', function (Builder $on) {
                $on->whereColumn('result.user_id', '=', 'profile.user_id');
                $on->whereColumn('result.course_child_id', '=', 'course_child.id');
            });
        
        return $query;
    }
}
